<?php
namespace Vg;

class Database
{
    protected static $connection = null;
    public static function connection($host, $database, $user, $password)
    {
        if (self::$connection == null) {
            try {
                self::$connection = new \PDO(
                    sprintf('mysql:host=%s;dbname=%s;charset=utf8', $host, $database),
                    $user,
                    $password,
                    array(\PDO::ATTR_EMULATE_PREPARES => false)
                );
            } catch (\PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                die;
            }

        }

        return self::$connection;
    }
}