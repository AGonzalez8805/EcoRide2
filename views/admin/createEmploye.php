<h1>Créer un compte employé</h1>

<?php if (isset($message)) : ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form method="POST" action="/?controller=admin&action=createEmploye">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email">

    <label for="pseudo">Pseudo :</label>
    <input type="text" name="pseudo" id="pseudo">

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password">

    <button type="submit">Créer</button>
</form>