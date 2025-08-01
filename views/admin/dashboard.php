<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="admin">
    <h1> Bienvenue - sur la page <?= htmlspecialchars($_SESSION['role']) ?> </h1>
    <div class="user-info">Connecté en tant qu'administrateur | Dernière connexion: aujourd'hui</div>
</div>

<div class="container admin-stat">
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
                    <input type="text" id="emp-pseudo" name="pseudo" required>
                </div>
                <div class="form-group">
                    <label for="emp-email">Email</label>
                    <input type="email" id="emp-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="emp-password">Mot de passe</label>
                    <input type="password" id="emp-password" name="password" required>
                </div>
                <button type="submit" class="btn">Créer le compte</button>
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
            <div class="user-list" id="user-list">
                <div class="user-item">
                    <div class="user-info-item">
                        <div class="user-name">Alice Martin</div>
                        <div class="user-email">alice.martin@email.com</div>
                    </div>
                    <span class="user-status status-active">Actif</span>
                    <button class="btn btn-danger" onclick="suspendUser('alice.martin@email.com')">Suspendre</button>
                </div>
                <div class="user-item">
                    <div class="user-info-item">
                        <div class="user-name">Bob Dupont</div>
                        <div class="user-email">bob.dupont@email.com</div>
                    </div>
                    <span class="user-status status-active">Actif</span>
                    <button class="btn btn-danger" onclick="suspendUser('bob.dupont@email.com')">Suspendre</button>
                </div>
                <div class="user-item">
                    <div class="user-info-item">
                        <div class="user-name">Claire Moreau</div>
                        <div class="user-email">claire.moreau@email.com</div>
                    </div>
                    <span class="user-status status-suspended">Suspendu</span>
                    <button class="btn" onclick="activateUser('claire.moreau@email.com')">Réactiver</button>
                </div>
            </div>
        </div>

        <!-- Gestion des employés -->
        <div class="card">
            <div class="card-header">
                <div class="card-icon">🛠️</div>
                <div class="card-title">Gestion des employés</div>
            </div>
            <div class="user-list" id="employee-list">

            </div>
        </div>
    </div>
</div>


<?php require_once APP_ROOT . '/views/footer.php'; ?>