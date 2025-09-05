<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Définition du jeu de caractères UTF-8 pour supporter les caractères spéciaux -->
    <meta charset="UTF-8">
    <!-- Configuration du viewport pour que le site soit responsive sur mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Brève description du site (utile pour le SEO et le partage sur les réseaux) -->
    <meta name="description" content="Covoiturage écologique facile et rapide. Partagez vos trajets, économisez de l'argent et réduisez votre empreinte carbone.">
    <!-- Nom de l'auteur -->
    <meta name="author" content="Amelie">
    <!-- Icône affichée dans l’onglet du navigateur -->
    <link rel="shortcut icon" href="/images/LOGO EcoRide  avec une voiture écologie.jpg" type="image/x-icon">
    <!-- Optimisation du chargement en préconnectant aux serveurs de Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Importation des polices Fira Code, Inter et Roboto depuis Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@300..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Importation de Bootstrap 5 via un CDN pour le style et la mise en page -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <!-- Lien vers la feuille de style CSS personnalisée -->
    <link rel="stylesheet" href="/css/main.css">
    <!-- Titre affiché dans l’onglet du navigateur -->
    <title>Ecoride</title>
</head>

<body>
    <header>
        <!-- Barre de navigation principale -->
        <nav class="navbar navbar-expand-lg">
            <div class="container d-flex">
                <!-- Logo du site aligné à gauche -->
                <div class="d-flex col-md-3 mb-2 mb-md-0 ms-5">
                    <a href="/?controller=pages&action=home" class="navbar-brand me-auto">
                        <img src="/images/LOGO EcoRide  avec une voiture écologie.jpg" alt="EcoRide logo" width="80">
                    </a>
                </div>
                <!-- Bouton burger pour afficher/masquer le menu sur mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Contenu du menu repliable -->
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <!-- Liens principaux centrés -->
                    <div class="navbar-nav text-center">
                        <a class="nav-link" href="/?controller=covoiturage&action=covoiturage">Covoiturage</a>
                        <a class="nav-link" href="/?controller=pages&action=contact">Contact</a>
                    </div>
                    <!-- Zone à droite : boutons Connexion/Inscription ou Compte/Déconnexion -->
                    <div class="d-flex flex-column flex-lg-row ms-lg-auto mt-3 mt-lg-0">
                        <!-- Si l'utilisateur est connecté -->
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Affichage selon le rôle -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <!-- Lien vers le tableau de bord administrateur -->
                                <a href="/?controller=admin&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord Admin</a>
                            <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'employe'): ?>
                                <!-- Lien vers le tableau de bord employé -->
                                <a href="/?controller=employe&action=dashboard" class="btn btn-outline-light btn-registration me-3">Tableau de bord Employé</a>
                                <!-- Si un type d'utilisateur spécifique est défini -->
                            <?php elseif (isset($_SESSION['typeUtilisateur'])): ?>
                                <?php
                                // Sélection dynamique du tableau de bord selon le type d’utilisateur
                                $dashboardRoute = match ($_SESSION['typeUtilisateur']) {
                                    'chauffeur' => '/?controller=user&action=dashboardChauffeur',
                                    'passager' => '/?controller=user&action=dashboardPassager',
                                    'chauffeur-passager' => '/?controller=user&action=dashboardMixte',
                                    default => '/?controller=user&action=dashboard',
                                };
                                ?>
                                <a href="<?= $dashboardRoute ?>" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                                <!-- Cas par défaut si connecté sans type précis -->
                            <?php else: ?>
                                <a href="/?controller=user&action=dashboard" class="btn btn-outline-light btn-registration me-3">Mon compte</a>
                            <?php endif; ?>
                            <!-- Bouton de déconnexion -->
                            <a href="/?controller=auth&action=logout" class="btn btn-outline-light btn-login me-3">Déconnexion</a>
                            <!-- Si l'utilisateur n'est pas connecté -->
                        <?php else: ?>
                            <!-- Bouton d'inscription -->
                            <a href="/?controller=auth&action=registration" class="btn btn-registration custom-btn me-lg-3 mb-2 mb-lg-0">Inscription</a>
                            <!-- Bouton de connexion -->
                            <a href="/?controller=auth&action=login" class="btn btn-login custom-btn mb-2 mb-lg-0">Connexion</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- Contenu principal de la page -->
    <main>