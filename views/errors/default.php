<?php require_once APP_ROOT . '/views/header.php'; ?>

<div class="dashboard-container">
    <?php if ($errors) { ?>
        <div class="simple-card">
            <div style="display: flex; align-items: flex-start; gap: 1rem;">
                <div style="flex-shrink: 0; margin-top: 0.25rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #dc2626;">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" />
                        <line x1="15" y1="9" x2="9" y2="15" stroke="currentColor" stroke-width="2" />
                        <line x1="9" y1="9" x2="15" y2="15" stroke="currentColor" stroke-width="2" />
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 600; color: #dc2626;">
                        Oops ! Une erreur s'est produite
                    </h3>
                    <p style="margin: 0 0 1.5rem 0; line-height: 1.5; color: #7f1d1d;">
                        <?= htmlspecialchars($errors); ?>
                    </p>
                    <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                        <button onclick="history.back()" class="btn-outline">
                            Retour
                        </button>
                        <a href="/?controller=pages&action=home" class="btn-profil">
                            Accueil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php require_once APP_ROOT . '/views/footer.php'; ?>