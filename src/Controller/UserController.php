<?php

namespace App\Controller;

use App\Db\MongoDb;
use App\Repository\TrajetRepository;
use App\Repository\VehiculeRepository;
use App\Repository\UserRepository;
use App\Repository\ParticipationRepository;
use App\Repository\AvisRepository;

class UserController extends Controller
{
    /**
     * Méthode de routage principale du contrôleur utilisateur.
     * Elle redirige vers la méthode appropriée en fonction de l'action passée en GET.
     */
    public function route(): void
    {
        $this->handleRoute(function () {
            if (isset($_GET['action'])) {
                switch ($_GET['action']) {
                    case 'dashboardChauffeur':
                        // Affiche le tableau de bord Chauffeur
                        $this->dashboardChauffeur();
                        break;

                    case 'profil':
                        // Affiche le profil utilisateur
                        $this->profil();
                        break;

                    case 'updateProfile':
                        $this->updateProfile();
                        break;

                    case 'updateRole':
                        $this->updateRole();
                        break;

                    case 'dashboardPassager':
                        // Affiche le tableau de bord Passager
                        $this->dashboardPassager();
                        break;

                    case 'dashboardMixte':
                        // Affiche le tableau de bord Passager/Chauffeur
                        $this->dashboardMixte();
                        break;

                    default:
                        // L'action n'est pas reconnue pour ce contrôleur
                        throw new \Exception("Action utilisateur inconnue");
                }
            }
        });
    }

    public function dashboardChauffeur(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $chauffeurId = $_SESSION['user_id'] ?? null;

        if (!$chauffeurId) {
            throw new \Exception("Utilisateur non connecté.");
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findById($chauffeurId);

        $trajetRepo = new TrajetRepository();
        $trajetsDuJour = $trajetRepo->findTodayByChauffeur($chauffeurId);

        $vehiculeRepo = new VehiculeRepository();
        $vehicules = $vehiculeRepo->findAllByUser($chauffeurId);

        $avisRepo = new AvisRepository(MongoDb::getInstance()->getDatabase());
        $avisValides = $avisRepo->listerValides(); // récupère tous les avis validés


        $this->render('user/dashboardChauffeur', [
            'user' => $user,
            'trajetsDuJour' => $trajetsDuJour,
            'vehicules' => $vehicules,
            'avisValides' => $avisValides
        ]);
    }

    public function profil(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: /?controller=security&action=login');
            exit;
        }

        $userRepo = new UserRepository();

        $user = $userRepo->findById($userId);

        if (!$user) {
            throw new \Exception("Utilisateur non trouvé.");
        }

        $this->render('user/profil', [
            'user' => $user,
        ]);
    }

    public function updateProfile(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Vérification session
        if (!$userId) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Utilisateur non connecté']);
                exit;
            } else {
                header('Location: /?controller=security&action=login');
                exit;
            }
        }

        $userRepo = new UserRepository();
        $field = $_POST['field'] ?? '';

        $response = ['success' => false];

        switch ($field) {
            case 'pseudo':
                if (!empty($_POST['pseudo'])) {
                    $newPseudo = trim($_POST['pseudo']);
                    $userRepo->updatePseudo($userId, $newPseudo);
                    $response['success'] = true;
                }
                break;

            case 'email':
                if (!empty($_POST['email'])) {
                    $newEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
                    if ($newEmail) {
                        $userRepo->updateEmail($userId, $newEmail);
                        $response['success'] = true;
                    } else {
                        $response['error'] = 'Email invalide';
                    }
                }
                break;

            case 'photo':
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $photoTmpPath = $_FILES['photo']['tmp_name'];
                    $photoExtension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                    $newPhotoName = uniqid('profile_') . '.' . $photoExtension;
                    $uploadDir = __DIR__ . '/../../public/photos/';

                    if (move_uploaded_file($photoTmpPath, $uploadDir . $newPhotoName)) {
                        $userRepo->updatePhoto($userId, $newPhotoName);
                        $response['success'] = true;
                        $response['photo'] = '/photos/' . $newPhotoName;
                    } else {
                        $response['error'] = 'Impossible d’enregistrer l’image';
                    }
                } else {
                    $response['error'] = 'Aucune image sélectionnée';
                }
                break;
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode($response);
            exit;
        } else {
            // Redirection classique
            header("Location: /?controller=user&action=profil");
            exit;
        }
    }

    public function updateRole(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérification que l'utilisateur est connecté
        if (empty($_SESSION['user_id'])) {
            header('Location: /?controller=auth&action=login');
            exit;
        }

        // Récupération du rôle choisi dans le formulaire
        $nouveauRole = $_POST['role'] ?? '';

        // Rôles autorisés
        $rolesAutorises = ['chauffeur', 'passager', 'chauffeur-passager'];

        if (!in_array($nouveauRole, $rolesAutorises)) {
            header('Location: /?controller=user&action=profil&error=role_invalide');
            exit;
        }

        // Mise à jour en base
        $userRepo = new UserRepository();
        $userRepo->updateRole($_SESSION['user_id'], $nouveauRole);

        // Mise à jour de la session
        $_SESSION['typeUtilisateur'] = $nouveauRole;

        // Redirection vers le dashboard correspondant
        switch ($nouveauRole) {
            case 'chauffeur':
                header('Location: /?controller=user&action=dashboardChauffeur');
                break;
            case 'passager':
                header('Location: /?controller=user&action=dashboardPassager');
                break;
            case 'chauffeur-passager':
                header('Location: /?controller=user&action=dashboardMixte');
                break;
        }
        exit;
    }

    public function dashboardPassager(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            throw new \Exception("Utilisateur non connecté.");
        }

        $userRepo = new UserRepository();
        $user = $userRepo->findById($userId);

        $participationRepo = new ParticipationRepository();
        $participationDuJour = $participationRepo->findTodayByUser($userId);

        $avisRepo = new AvisRepository(MongoDb::getInstance()->getDatabase());
        $mesAvis = $avisRepo->listerAvecStatut($userId);

        $this->render('user/dashboardPassager', [
            'user' => $user,
            'participationDuJour' => $participationDuJour,
            'mesAvis' => $mesAvis
        ]);
    }


    public function dashboardMixte(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->render('user/dashboardMixte');
    }
}
