<?php
session_start();

define('APP_ROOT', dirname(__DIR__));

define('APP_ENV', dirname(__DIR__) . '/.db.ini');

require __DIR__ . '/../vendor/autoload.php';

use App\Controller\Controller;

$controller = new Controller();
$controller->route();

use App\Db\Mysql;

$mysql = Mysql::getInstance();
