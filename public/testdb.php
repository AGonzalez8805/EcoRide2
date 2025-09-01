<?php
// Test de connexion à la base via JAWSDB_URL

$databaseUrl = getenv('JAWSDB_URL');

if (!$databaseUrl) {
    die("La variable d'environnement JAWSDB_URL n'est pas définie.");
}

$url = parse_url($databaseUrl);

$host = $url['host'];
$user = $url['user'];
$password = $url['pass'];
$port = $url['port'] ?? 3306;
$dbname = ltrim($url['path'], '/');

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données ! 🎉";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
