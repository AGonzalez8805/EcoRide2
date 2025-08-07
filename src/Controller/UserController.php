<?php

namespace App\Controller;

use App\Repository\TrajetRepository;
use App\Repository\VehiculeRepository;
use App\Repository\UserRepository;

class UserController extends Controller
{
    /**
     * Méthode de routage principale du contrôleur utilisateur.
     * Elle redirige vers la méthode appropriée en fonction de l'action passée en GET.
     */
    public function route(): void
    {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'dashboardChauffeur':
                    // Affiche le tableau de bord Ch
                    $this->dashboardChauffeur();
                    break;

                case 'profilChauffeur':
                    // Affiche le profil utilisateur
                    $this->profilChauffeur();
                    break;

                case 'updateProfile':
                    $this->updateProfile();
                    break;

                case 'dashboardPassager':
                    // Affiche le tableau de bord utilisateur
                    $this->dashboardPassager();
                    break;

                case 'dashboardMixte':
                    // Affiche le tableau de bord utilisateur
                    $this->dashboardMixte();
                    break;

                default:
                    // L'action n'est pas reconnue pour ce contrôleur
                    throw new \Exception("Action utilisateur inconnue");
            }
        }
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

        $trajetRepo = new TrajetRepository();
        $trajetsDuJour = $trajetRepo->findTodayByChauffeur($chauffeurId);

        $this->render('user/dashboardChauffeur', [
            'trajetsDuJour' => $trajetsDuJour
        ]);
    }

    public function profilChauffeur(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            header('Location: /?controller=security&action=login');
            exit;
        }

        $userRepo = new UserRepository();
        $vehiculeRepo = new VehiculeRepository();

        $user = $userRepo->findById($userId);

        if (!$user) {
            throw new \Exception("Utilisateur non trouvé.");
        }

        $vehicules = $vehiculeRepo->findAllByUser($userId);

        $this->render('user/profilChauffeur', [
            'user' => $user,
            'vehicules' => $vehicules
        ]);
    }

    public function updateProfile(): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /?controller=security&action=login');
            exit;
        }

        $userRepo = new UserRepository();

        // ✅ Mise à jour du pseudo (si fourni)
        if (!empty($_POST['pseudo'])) {
            $newPseudo = trim($_POST['pseudo']);
            $userRepo->updatePseudo($userId, $newPseudo);
        }

        // Mise à jour de l'email
        if (!empty($_POST['email'])) {
            $newEmail = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
            if ($newEmail) {
                $userRepo->updateEmail($userId, $newEmail);
            } else {
                // Gestion d'erreur basique (optionnelle)
                // Par exemple, ajouter un message d'erreur en session
            }
        }

        // ✅ Mise à jour de la photo (si envoyée)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photoTmpPath = $_FILES['photo']['tmp_name'];
            $photoName = basename($_FILES['photo']['name']);
            $photoExtension = pathinfo($photoName, PATHINFO_EXTENSION);

            $newPhotoName = uniqid('profile_') . '.' . $photoExtension;
            $uploadDir = __DIR__ . '/../../public/photos/';
            $destination = $uploadDir . $newPhotoName;

            if (!move_uploaded_file($photoTmpPath, $destination)) {
                throw new \Exception("Erreur lors du téléchargement de la photo.");
            }

            $userRepo->updatePhoto($userId, $newPhotoName);
        }

        // 🔁 Redirection propre
        header("Location: /?controller=user&action=profilChauffeur");
        exit;
    }

    public function dashboardPassager(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupérer les infos utilisateur stockées en session
        $user = [
            'firstName' => $_SESSION['firstName'] ?? 'Utilisateur',
            'name' => $_SESSION['name'] ?? '',
            'email' => $_SESSION['email'] ?? ''
        ];

        $this->render('user/dashboardPassager', ['user' => $user]);
    }

    public function dashboardMixte(): void
    {
        $this->render('user/dashboardMixte');
    }
}
