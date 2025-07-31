<?php require_once APP_ROOT . '/views/header.php'; ?>

<h1>Espace Administrateur</h1>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['role']) ?>.</p>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoRide - Tableau de Bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="/css/admin_dashboard.css" rel="stylesheet">
</head>

<body>

    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white p-3">EcoRide Admin</div>
            <div class="list-group list-group-flush">
                <a href="/admin/dashboard" class="list-group-item list-group-item-action bg-dark text-white active">
                    <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="/admin/users" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-fw fa-users"></i> Gestion des Utilisateurs
                </a>
                <a href="/admin/analytics" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-fw fa-chart-line"></i> Analyse & Rapports
                </a>
                <a href="/admin/moderation" class="list-group-item list-group-item-action bg-dark text-white">
                    <i class="fas fa-fw fa-shield-alt"></i> Modération
                </a>
            </div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <button class="btn btn-primary" id="menu-toggle">Toggle Menu</button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Bienvenue, <?php echo htmlspecialchars($_SESSION['admin_pseudo'] ?? 'Admin'); ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="/admin/profile">Mon Profil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/admin/logout">Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid p-4">
                <?php include $viewPath; // Variable passée par le contrôleur 
                ?>
            </div>
        </div>
    </div>


    <?php require_once APP_ROOT . '/views/footer.php'; ?>