<h2>Résultats de la recherche</h2>

<?php if (!empty($results)) : ?>
    <ul>
        <?php foreach ($results as $trajet) : ?>
            <li>
                De <?= htmlspecialchars($trajet['lieuDepart']) ?>
                à <?= htmlspecialchars($trajet['lieuArrivee']) ?>
                le <?= htmlspecialchars($trajet['dateDepart']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Aucun résultat trouvé.</p>
<?php endif; ?>