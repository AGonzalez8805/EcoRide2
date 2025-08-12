<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <div class="header-layout">
            <!-- Photo de profil à gauche -->
            <div class="header-photo-left">
                <?php if (!empty($user->getPhoto())): ?>
                    <!-- Affichage de la photo (cliquable) -->
                    <form action="/?controller=user&action=updateProfile" method="POST" enctype="multipart/form-data" id="photoForm">
                        <input type="file" name="photo" id="photoInput" accept="image/*" style="display: none;" onchange="document.getElementById('photoForm').submit();">

                        <label for="photoInput" style="cursor: pointer;">
                            <img src="/photos/<?= htmlspecialchars($user->getPhoto()) ?>" alt="Profil" class="profile-photo-header">
                        </label>
                    </form>
                <?php else: ?>
                    <div class="no-photo-header">👤</div>
                <?php endif; ?>
            </div>
            <!-- Nom centré -->
            <div class="header-name-center">
                <h1><strong><?= htmlspecialchars($user->getName()) ?> <?= htmlspecialchars($user->getFirstName()) ?></strong></h1>
            </div>

            <!-- Espace pour équilibrer (invisible) -->
            <div class="header-spacer"></div>
        </div>
    </div>
</section>

<div class="container mt-4">

    <!-- Informations -->
    <div class="info-card" id="profilForm">
        <h3>📋 Mes informations</h3>
        <div class="info-grid">
            <!-- Nom -->
            <div class="info-item">
                <span class="label">Nom :</span>
                <span class="value"><?= htmlspecialchars($user->getName()) ?></span>
            </div>
            <!-- Prénom -->
            <div class="info-item">
                <span class="label">Prénom :</span>
                <span class="value"><?= htmlspecialchars($user->getFirstName()) ?></span>
            </div>
            <!-- Pseudo -->
            <div class="info-item">
                <span class="label">Pseudo :</span>
                <!-- Affichage du pseudo + icon -->
                <div class="pseudo-display" id="pseudoDisplayContainer">
                    <span class="value"><?= htmlspecialchars($user->getPseudo()) ?></span>
                    <button type="button" onclick="editPseudo()" class="btn-icon" title="Modifier le pseudo">✏️</button>
                </div>
                <!-- Formulaire de modification (caché par défaut) -->
                <form action="/?controller=user&action=updateProfile" method="POST" id="pseudoForm" style="display: none;">
                    <div class="pseudo-edit">
                        <input type="text" name="pseudo" id="pseudoInput" value="<?= htmlspecialchars($user->getPseudo()) ?>" required>
                        <button type="submit" title="Enregistrer" class="btn-small">✅</button>
                        <button type="button" onclick="cancelEditPseudo()" class="btn-small">❌</button>
                    </div>
                </form>
            </div>
            <!-- Adresse email -->
            <div class="info-item">
                <span class="label">Email :</span>
                <!-- Affichage de l'email + icon -->
                <div class="email-display" id="emailDisplayContainer">
                    <span class="value"><?= htmlspecialchars($user->getEmail()) ?></span>
                    <button type="button" onclick="editMail()" class="btn-icon" title="Modifier l'email">📧</button>
                </div>
                <!-- Formulaire de modification (caché par défaut) -->
                <form action="/?controller=user&action=updateProfile" method="POST" id="emailForm" style="display: none;">
                    <div class="email-edit">
                        <input type="email" name="email" id="mailInput" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                        <button type="submit" title="Enregistrer" class="btn-small">✅</button>
                        <button type="button" onclick="cancelEditMail()" class="btn-small">❌</button>
                    </div>
                </form>
            </div>
            <div class="info-item">
                <span class="label">Crédits :</span>
                <span class="value credit-badge"><?= htmlspecialchars($user->getCredit()) ?> €</span>
            </div>
            <div class="info-item">
                <span class="label">Statut :</span>
                <span class="value <?= $user->getIsSuspended() ? 'status-bad' : 'status-good' ?>">
                    <?= $user->getIsSuspended() ? '❌ Suspendu' : '✅ Actif' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Véhicules -->
    <div class="info-card">
        <h3>🚗 Mes véhicules</h3>
        <?php var_dump($vehicules); ?>
        <?php if (!empty($vehicules)): ?>
            <div class="vehicle-list">
                <?php foreach ($vehicules as $vehicule): ?>
                    <div class="vehicle-box">
                        <strong><?= htmlspecialchars($vehicule->getMarque()) ?> <?= htmlspecialchars($vehicule->getModele()) ?></strong>
                        <div class="vehicle-info">
                            <span>🔢 <?= htmlspecialchars($vehicule->getImmatriculation()) ?></span>
                            <span>👥 <?= $vehicule->getNbPlaces() ?> places</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-vehicle">
                Vous n'avez pas encore de véhicule enregistré
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>