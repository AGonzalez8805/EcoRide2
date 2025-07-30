<?php require_once APP_ROOT . '/views/header.php'; ?>

<h1>Espace Administrateur</h1>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['role']) ?>.</p>
<a href="/?controller=auth&action=logout">Déconnexion</a>


<?php require_once APP_ROOT . '/views/footer.php'; ?>