<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;

class PDOQueryBuilder
{
    protected $table;
    protected $connection;
    protected $conditions;
    protected $value;

    public function __construct(DatabaseConnectionInterface $connection)
    {
        $this->connection = $connection->getConnection();
    }

    public function table(string $table)
    {
        $this->table = $table;
        return $this;
    }

    public function create(array $data)
    {
        $placeholder = [];
        foreach ($data as $column => $value) {
            $placeholder[] = '?';
        }

        $fields = implode(',', array_keys($data));
        $placeholder = implode(',', $placeholder);

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";

        $query = $this->connection->prepare($sql);
        $query->execute(array_values($data));

        return (int)$this->connection->lastInsertId();
    }

    public function where(string $column, string $value)
    {
        $this->conditions[] = "{$column}=?";
        $this->values[] = $value;
        return $this;
    }

    public function update(array $data)
    {
        $fields = [];
        foreach ($data as $column => $value) {
            $fields[] = "{$column}='{$value}'";
        }
        $fields = implode(', ', $fields);
        $conditions = implode(' and ', $this->conditions);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$conditions}";
        $query = $this->connection->prepare($sql);
        $query->execute($this->values);
        return $query->rowCount();
    }
}
