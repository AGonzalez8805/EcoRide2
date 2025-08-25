<?php

namespace App\Db;

use MongoDB\Client;
use MongoDB\Database;

class MongoDb
{
    private string $dbName;
    private ?Client $client = null;
    private static ?self $instance = null;

    private function __construct()
    {
        $mongoUrl = getenv('MONGODB_URI');

        if ($mongoUrl) {
            // En prod Heroku, on utilise l'URI tel quel
            $this->client = new Client($mongoUrl);
            $this->dbName = ltrim(parse_url($mongoUrl, PHP_URL_PATH), '/');
        } else {
            // En local, on lit depuis le fichier .db.ini
            $dbConf = parse_ini_file(APP_ENV);
            $dbHost = $dbConf["mongo_host"];
            $dbUser = rawurlencode($dbConf["mongo_user"]);
            $dbPassword = rawurlencode($dbConf["mongo_password"]);
            $dbPort = $dbConf["mongo_port"];
            $this->dbName = $dbConf["mongo_name"];

            $uri = "mongodb://{$dbUser}:{$dbPassword}@{$dbHost}:{$dbPort}/{$this->dbName}?authSource=admin";
            $this->client = new Client($uri);
        }
    }

    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new MongoDb();
        }
        return self::$instance;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function getDatabase(): Database
    {
        return $this->client->selectDatabase($this->dbName);
    }
}
