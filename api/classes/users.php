<?php

class Users
{

    private object $connection;
    private string $table_name = "users";

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function findByEmail(string $email): ?array
    {
        $query = sprintf(
            "select * from %s where email = ? limit 1",
            $this->table_name
        );

        $pdo = $this->connection->prepare($query);
        $pdo->bindParam(1, $email);
        $pdo->execute();

        if ($pdo->rowCount() > 0) {
            return $pdo->fetch(PDO::FETCH_ASSOC);
        }

        return null;
    }

    public function updatePhoneUser($id, $phone) {
        $query = sprintf(
            "update %s set phone = $phone where id = $id",
            $this->table_name
        );

        $pdo = $this->connection->prepare($query);
        $pdo->execute();
    }
}