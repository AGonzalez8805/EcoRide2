<?php require_once APP_ROOT . '/views/header.php'; ?>

<h1>Bienvenue Employé</h1>
<p>Bonjour <?= htmlspecialchars($_SESSION['pseudo'] ?? '') ?>, vous êtes connecté en tant qu'employé.</p>

<?php require_once APP_ROOT . '/views/footer.php'; ?>