<?php

namespace Api\Models;

class Users
{
    private object $connection;
    private string $tableName = "users";
    private array $fields = ['id', 'firstname', 'lastname', 'email', 'password', 'created', 'modified', 'vat'];

    public function __construct($db)
    {
        $this->connection = $db;
    }

    private function prepareParams(array $data): string
    {
        $params = [];
        foreach ($data as $field => $value) {
            if (in_array($field, $this->fields)) {
                $params[] = "$field = '$value'";
            }
        }
        return implode(', ', $params);
    }

    public function getList(array $data = []): array
    {
        $paramsText = $this->prepareParams($data);

        $query = sprintf(
            "select * from %s %s",
            $this->tableName,
            !empty($params) ? "where $paramsText" : ""
        );

        $pdo = $this->connection->prepare($query);
        $pdo->execute();

        return $pdo->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update(array $updateData, array $selectData): bool
    {
        $updateData = $this->prepareParams($updateData);
        $selectData = $this->prepareParams($selectData);

        if (empty($selectData)) {
            return false;
        }
        $query = "update " . $this->tableName . " set $updateData where $selectData";
        $pdo = $this->connection->prepare($query);
        return $pdo->execute();
    }

    public function delete(array $data = []): bool
    {
        $paramsText = $this->prepareParams($data);

        $query = sprintf(
            "delete from %s %s",
            $this->tableName,
            !empty($params) ? "where $paramsText" : ""
        );

        $pdo = $this->connection->prepare($query);
        return $pdo->execute();
    }
}