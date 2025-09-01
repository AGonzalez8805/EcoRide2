<?php require_once APP_ROOT . '/views/header.php'; ?>

<section class="en-tete">
    <div class="container">
        <h1>Résultats</h1>
    </div>
</section>

<section class="container">
    <?php if (empty($trajets)) : ?>
        <p>Aucun covoiturage trouvé pour les critères indiqués.</p>
    <?php else : ?>
        <?php foreach ($trajets as $covoit) : ?>
            <div class="card-search">
                <ul class="list-group">
                    <li class="list-group-item">
                        <p><strong>Départ :</strong> <?= htmlspecialchars($covoit->getLieuDepart()) ?> le <?= $covoit->getDateDepart() ?> à <?= $covoit->getHeureDepart() ?></p>
                    </li>
                    <li class="list-group-item">
                        <p><strong>Arrivée :</strong> <?= htmlspecialchars($covoit->getLieuArrivee()) ?> le <?= $covoit->getDateArrivee() ?> à <?= $covoit->getHeureArrivee() ?></p>
                    </li>
                    <li class="list-group-item">
                        <p><strong>Places restantes :</strong> <?= $covoit->getNbPlace() ?> | <strong>Prix :</strong> <?= $covoit->getPrixPersonne() ?> crédits</p>
                    </li>
                    <li class="list-group-item">
                        <p><strong>Statut :</strong> <?= $covoit->getStatut() ?></p>
                    </li>
                </ul>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php require_once APP_ROOT . '/views/footer.php'; ?>