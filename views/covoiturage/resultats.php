<?php require_once APP_ROOT . '/views/header.php'; ?>

<h2>Résultats de covoiturage</h2>

<h2>Résultats de votre recherche</h2>

<?php if (empty($resultats)) : ?>
    <p>Aucun covoiturage trouvé pour les critères indiqués.</p>
<?php else : ?>
    <?php foreach ($resultats as $covoit) : ?>
        <div class="covoiturage-card">
            <p><strong>Départ :</strong> <?= htmlspecialchars($covoit['lieuDepart']) ?> le <?= $covoit['dateDepart'] ?> à <?= $covoit['heureDepart'] ?></p>
            <p><strong>Arrivée :</strong> <?= htmlspecialchars($covoit['lieuArrivee']) ?> le <?= $covoit['dateArrivee'] ?> à <?= $covoit['heureArrivee'] ?></p>
            <p><strong>Places restantes :</strong> <?= $covoit['nbPlace'] ?> | <strong>Prix :</strong> <?= $covoit['prixPersonne'] ?> crédits</p>
            <p><strong>Statut :</strong> <?= $covoit['statut'] ?></p>
            <?php if (!empty($covoit['energie']) && strtolower($covoit['energie']) === 'électrique') : ?>
                <p>🔋 <strong>Voyage écologique (voiture électrique)</strong></p>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>



<?php require_once APP_ROOT . '/views/footer.php'; ?>