<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1> Bienvenue - sur la page <?= htmlspecialchars($_SESSION['role']) ?> </h1>
        <div class="user-info">Connecté en tant qu'administrateur | Dernière connexion: aujourd'hui</div>
    </div>
</section>


<div class="container admin-stat" id="adminStat">
    <div class="dashboard-grid">
        <!-- Statistiques -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">📊</div>
                <div class="card-title">Statistiques de la plateforme</div>
            </div>
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" id="daily-rides">
                        <?= $stats['trajetsParJour'][array_key_last($stats['trajetsParJour'])]['nb'] ?? 0 ?>
                    </div>
                    <div class="stat-label">Covoiturages aujourd'hui</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="daily-credits">
                        <?= $stats['creditsParJour'][array_key_last($stats['creditsParJour'])]['total'] ?? 0 ?>
                    </div>
                    <div class="stat-label">Crédits gagnés aujourd'hui</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="total-credits">
                        <?= $stats['totalCredits'] ?>
                    </div>
                    <div class="stat-label">Total crédits gagnés</div>
                </div>
            </div>
        </div>

        <!-- Création de compte employé -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">👤</div>
                <div class="card-title">Créer un compte employé</div>
            </div>
            <form id="employee-form" method="post" action="/?controller=admin&action=createEmploye">
                <div class="form-group">
                    <label for="emp-pseudo">Pseudo</label>
                    <input type="text" id="emp-pseudo" name="pseudo">
                </div>
                <div class="form-group">
                    <label for="emp-email">Email</label>
                    <input type="email" id="emp-email" name="email">
                </div>
                <div class="form-group">
                    <label for="emp-password">Mot de passe</label>
                    <input type="password" id="emp-password" name="password">
                </div>
                <button type="submit" class="btn-dashboard">Créer le compte</button>
                <div id="employee-message" class="message"></div>
            </form>
        </div>

        <!-- Gestion des utilisateurs -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">👥</div>
                <div class="card-title">Gestion des utilisateurs</div>
            </div>
            <div class="form-group">
                <input type="text" id="search-user" placeholder="Rechercher un utilisateur...">
            </div>
            <div class="user-list" id="user-list"></div>
        </div>

        <!-- Gestion des employés -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🛠️</div>
                <div class="card-title">Gestion des employés</div>
            </div>
            <div class="user-list" id="employee-list"></div>
        </div>
    </div>


    <?php require_once APP_ROOT . '/views/footer.php'; ?>