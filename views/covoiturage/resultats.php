<?php require_once APP_ROOT . '/views/header.php'; ?>

<h2>Résultats de covoiturage</h2>
<h2>Résultats de votre recherche</h2>

<?php if (empty($trajets)) : ?>
    <p>Aucun covoiturage trouvé pour les critères indiqués.</p>
<?php else : ?>
    <?php foreach ($trajets as $covoit) : ?>
        <div class="covoiturage-card">
            <p><strong>Départ :</strong> <?= htmlspecialchars($covoit->getLieuDepart()) ?> le <?= $covoit->getDateDepart() ?> à <?= $covoit->getHeureDepart() ?></p>
            <p><strong>Arrivée :</strong> <?= htmlspecialchars($covoit->getLieuArrivee()) ?> le <?= $covoit->getDateArrivee() ?> à <?= $covoit->getHeureArrivee() ?></p>
            <p><strong>Places restantes :</strong> <?= $covoit->getNbPlace() ?> | <strong>Prix :</strong> <?= $covoit->getPrixPersonne() ?> crédits</p>
            <p><strong>Statut :</strong> <?= $covoit->getStatut() ?></p>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once APP_ROOT . '/views/footer.php'; ?>