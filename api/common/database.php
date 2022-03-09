<?php

namespace Api\Common;

use PDO;
use PDOException;

class Database
{
    private static string $host = "localhost";
    private static string $db_name = "vies";
    private static string $username = "ndrw39";
    private static string $password = "";
    public static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (isset(self::$connection)) {
            return self::$connection;
        }

        try {
            $dsn = sprintf("pgsql:host=%s;port=5432;dbname=%s;", self::$host, self::$db_name);
            self::$connection = new PDO(
                $dsn,
                self::$username,
                self::$password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return self::$connection;
    }
}

?>