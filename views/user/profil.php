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
        <div class="info-grid" id="profilContainer">
            <!-- Nom -->
            <div class="info-item">
                <span class="label">Nom :</span>
                <span class="value"><?= htmlspecialchars($user->getName()) ?></span>
            </div>
            <!-- Prenom -->
            <div class="info-item">
                <span class="label">Prénom :</span>
                <span class="value"><?= htmlspecialchars($user->getFirstName()) ?></span>
            </div>
            <!-- Pseudo -->
            <div class="info-item">
                <span class="label">Pseudo :</span>
                <div class="pseudo-display" id="pseudoDisplayContainer">
                    <span class="value"><?= htmlspecialchars($user->getPseudo()) ?></span>
                    <button type="button" id="editPseudoBtn" class="btn-icon" title="Modifier le pseudo">✏️</button>
                </div>
                <form action="/?controller=user&action=updateProfile" method="POST" id="pseudoForm" style="display: none;">
                    <input type="hidden" name="field" value="pseudo">
                    <div class="pseudo-edit">
                        <input type="text" name="pseudo" id="pseudoInput" value="<?= htmlspecialchars($user->getPseudo()) ?>" required>
                        <button type="submit" id="savePseudoBtn" title="Enregistrer" class="btn-small">✅</button>
                        <button type="button" id="cancelPseudoBtn" class="btn-small">❌</button>
                    </div>
                </form>
            </div>
            <!-- Mail -->
            <div class="info-item">
                <span class="label">Email :</span>
                <div class="email-display" id="emailDisplayContainer">
                    <span class="value"><?= htmlspecialchars($user->getEmail()) ?></span>
                    <button type="button" id="editMailBtn" class="btn-icon" title="Modifier l'email">📧</button>
                </div>
                <form action="/?controller=user&action=updateProfile" method="POST" id="emailForm" style="display: none;">
                    <input type="hidden" name="field" value="email">
                    <div class="email-edit">
                        <input type="email" name="email" id="mailInput" value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                        <button type="submit" id="saveMailBtn" title="Enregistrer" class="btn-small">✅</button>
                        <button type="button" id="cancelMailBtn" class="btn-small">❌</button>
                    </div>
                </form>
            </div>
            <!-- Role -->
            <div class="info-item">
                <span class="label">Je souhaite être :</span>
                <form action="/?controller=user&action=updateRole" method="POST">
                    <select class="form-select" name="role" required>
                        <option value="" disabled <?= empty($user->getRole()) ? 'selected' : '' ?>>Choisissez une option</option>
                        <option value="passager" <?= $user->getRole() === 'passager' ? 'selected' : '' ?>>Passager</option>
                        <option value="chauffeur" <?= $user->getRole() === 'chauffeur' ? 'selected' : '' ?>>Chauffeur</option>
                        <option value="chauffeur-passager" <?= $user->getRole() === 'chauffeur-passager' ? 'selected' : '' ?>>Chauffeur & Passager</option>
                    </select>
                    <button type="submit" class="btn-small mt-2">Mettre à jour</button>
                </form>
            </div>
            <!-- Credit -->
            <div class="info-item">
                <span class="label">Crédits :</span>
                <span class="value credit-badge"><?= htmlspecialchars($user->getCredit()) ?> €</span>
            </div>
            <!-- Statut-->
            <div class="info-item">
                <span class="label">Statut :</span>
                <span class="value <?= $user->isIsSuspended() ? 'status-bad' : 'status-good' ?>">
                    <?= $user->isIsSuspended() ? '❌ Suspendu' : '✅ Actif' ?>
                </span>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>