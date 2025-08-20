<?php

namespace App\Db;

use MongoDB\Client;
use MongoDB\Database;

class MongoDb
{

    // Propriétés de configuration de la base de données
    private string $dbName;
    private string $dbUser;
    private string $dbPassword;
    private string $dbPort;
    private string $dbHost;

    // Instance MongoDB\Client
    private ?Client $client = null;

    //Instance unique de la classe (singleton)
    private static ?self $instance = null;

    // COnstructeur privé pour empêcher l'instanciation directe (pattern Singletion)
    private function __construct()
    {
        //Récupération de la config depuis DATABASE_URL (pour heroku)
        $databaseUrl = getenv('DATABASE_URL');


        if ($databaseUrl) {
            $url = parse_url($databaseUrl);
            $this->dbHost = $url["host"];
            $this->dbUser = $url["user"];
            $this->dbPassword = $url["pass"];
            $this->dbPort = $url["port"] ?? 3306;
            $this->dbName = ltrim($url["path"], '/');
        } else {
            // Sinon on charge depuis le fichier ini
            $dbConf = parse_ini_file(APP_ENV);
            $this->dbHost = $dbConf["mongo_host"];
            $this->dbUser = rawurlencode($dbConf["mongo_user"]); // encode les caractères spéciaux
            $this->dbPassword = rawurlencode($dbConf["mongo_password"]);
            $this->dbPort = $dbConf["mongo_port"];
            $this->dbName = $dbConf["mongo_name"];
        }
    }

    //Retourne l'instance unique de Mysql (Singleton)
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new MongoDb();
        }
        return self::$instance;
    }

    // Retourne l'objet MongoDB\Client connecté à la base de données
    public function getClient(): Client
    {
        if (is_null($this->client)) {
            $uri = "mongodb://{$this->dbUser}:{$this->dbPassword}"
                . "@{$this->dbHost}:{$this->dbPort}/{$this->dbName}"
                . "?authSource=admin";
            $this->client = new Client($uri);
        }
        return $this->client;
    }

    //Retourne la base de données MongoDB
    public function getDatabase(): Database
    {
        return $this->getClient()->selectDatabase($this->dbName);
    }
}
