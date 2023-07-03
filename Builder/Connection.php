<?php
namespace App\Builder;
class Connection
{

    protected static $instance;

    private static $dsn = 'mysql:host=localhost;dbname=cartonplast';

    private static $username = 'root';

    private static $password = '';

    private function __construct()
    {
        try {
            self::$instance = new \PDO(self::$dsn, self::$username, self::$password);
        } catch (\PDOException $e) {
            echo "MySql Connection Error: " . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            new Connection();
        }

        return self::$instance;
    }
}
