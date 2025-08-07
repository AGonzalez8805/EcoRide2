<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Définition du jeu de caractères -->
    <meta charset="UTF-8">
    <!-- Configuration du viewport pour le responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Description de la page (utile pour le SEO) -->
    <meta name="description" content="site de covoiturage">
    <link rel="shortcut icon" href="/images/LOGO EcoRide  avec une voiture écologie.jpg" type="image/x-icon">
    <!-- Préconnexion pour améliorer le chargement des polices Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Importation des polices Google -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Importation de Bootstrap 5 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Lien vers la feuille de style personnalisée -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Titre de la page affiché dans l'onglet du navigateur -->
    <title>Ecoride</title>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container d-flex">
                <!-- Logo à gauche -->
                <div class="d-flex col-md-3 mb-2 mb-md-0 ms-5">
                    <a href="/page?action=home" class="navbar-brand me-auto">
                        <img src="/images/LOGO EcoRide  avec une voiture écologie.jpg" alt="EcoRide logo" width="80">
                    </a>
                </div>

                <!-- Bouton burger pour mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Contenu repliable -->
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <!-- Liens du milieu -->
                    <div class="navbar-nav text-center">
                        <a class="nav-link" href="/?controller=covoiturage&action=covoiturage">Covoiturage</a>
                        <a class="nav-link" href="/?controller=pages&action=contact">Contact</a>
                    </div>
                    <!-- Connexion/Inscription/compte à droite -->
                    <div class="d-flex flex-column flex-lg-row ms-lg-auto mt-3 mt-lg-0">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="/?controller=admin&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord Admin</a>
                            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'employe'): ?>
                                <a href="/?controller=employe&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord Employé</a>
                            <?php elseif (isset($_SESSION['typeUtilisateur'])): ?>
                                <?php
                                $dashboardRoute = match ($_SESSION['typeUtilisateur']) {
                                    'chauffeur' => '/?controller=user&action=dashboardChauffeur',
                                    'passager' => '/?controller=user&action=dashboardPassager',
                                    'chauffeur-passager' => '/?controller=user&action=dashboardMixte',
                                    default => '/?controller=user&action=dashboard',
                                };
                                ?>
                                <a href="<?= $dashboardRoute ?>" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                            <?php else: ?>
                                <a href="/?controller=user&action=dashboard" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                            <?php endif; ?>
                            <a href="/?controller=auth&action=logout" class="btn btn-outline-light btn-login me-3">Déconnexion</a>
                        <?php else: ?>
                            <a href="/?controller=auth&action=registration" class="btn btn-registration custom-btn me-lg-3 mb-2 mb-lg-0">Inscription</a>
                            <a href="/?controller=auth&action=login" class="btn btn-login custom-btn mb-2 mb-lg-0">Connexion</a>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main>