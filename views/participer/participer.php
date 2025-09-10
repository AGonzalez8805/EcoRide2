<?php require_once APP_ROOT . '/views/header.php'; ?>

<form method="post" action="/?controller=participation&action=participer">
    <input type="hidden" name="trajet_id" value="<?= $trajet->getId() ?>">
    <label for="nb_place">Nombre de places :</label>
    <input type="number" name="nb_place" min="1" max="<?= $trajet->getNbPlace() ?>" value="1">
    <button type="submit" class="btn-profil">Réserver</button>
</form>

<?php require_once APP_ROOT . '/views/footer.php'; ?>