<?php require_once APP_ROOT . '/views/header.php'; ?>

<h1>Espace Utilisateur</h1>
<p>Bienvenue, <?= htmlspecialchars($_SESSION['role']) ?>.</p>



<?php require_once APP_ROOT . '/views/footer.php'; ?>