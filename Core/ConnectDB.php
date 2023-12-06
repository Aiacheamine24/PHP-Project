<?php
// Connect to database 
namespace App\Core;
// Class ConnectDB
class ConnectDB extends \PDO
{
    // Properties
    protected $dbhost = 'localhost';
    protected $dbname = 'projet_eco_php';
    protected $dbuser = 'root';
    protected $dbpass = '';
    protected $dbcharset = 'utf8';
    protected static $instance;
    // Methods
    public function __construct()
    {
        // DSN
        $dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname . ';charset=' . $this->dbcharset;
        try {
            // PDO instance
            self::$instance = new \PDO($dsn, $this->dbuser, $this->dbpass);
            // PDO error mode
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            // PDO fetch mode
            self::$instance->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
    public static function getInstance(): \PDO
    {
        if (is_null(self::$instance)) {
            self::$instance = new ConnectDB();
        }
        return self::$instance;
    }
}
