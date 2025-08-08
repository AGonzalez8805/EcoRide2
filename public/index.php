<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();


define('APP_ROOT', dirname(__DIR__));

// Chemin vers fichier de config DB
define('APP_ENV', dirname(__DIR__) . '/.db.ini');

// Chargement du fichier .env (Docker, API, etc.)
$envPath = dirname(__DIR__) . '/.env';
if (file_exists($envPath)) {
    $envVars = parse_ini_file($envPath);
    foreach ($envVars as $key => $value) {
        $_ENV[$key] = $value;
    }
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controller\Controller;

$controller = new Controller();
$controller->route();

use App\Db\Mysql;

$mysql = Mysql::getInstance();
