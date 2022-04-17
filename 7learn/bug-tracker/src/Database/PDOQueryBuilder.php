<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use PDO;

class PDOQueryBuilder
{
    protected $table;
    protected $connection;
    protected $conditions;
    protected $value;
    protected $statement;

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

        $this->values = array_values($data);

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";

        $this->execute($sql);

        return (int)$this->connection->lastInsertId();
    }

    public function where(string $column, string $value)
    {
        if (is_null($this->conditions)) {
            $this->conditions = "{$column}=?";
        } else {
            $this->conditions .= " and {$column}=?";
        }

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

        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statement->rowCount();
    }

    public function delete()
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statement->rowCount();
    }

    public function get(array $columns = ['*'])
    {
        $columns = implode(',', $columns);

        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statement->fetchAll();
    }

    public function first(array $columns = ['*'])
    {
        $data = $this->get($columns);
        return empty($data) ? null : $data[0];
    }

    public function find(int $id)
    {
        return $this->where('id', $id)->first();
    }

    public function findBy(string $column, $value)
    {
        return $this->where($column, $value)->first();
    }

    public function truncateAllTable()
    {
        $query = $this->connection->prepare("SHOW TABLES");
        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_COLUMN) as $table) {
            $this->connection->prepare("TRUNCATE TABLE `{$table}`")->execute();
        }
    }

    public function beginTransaction()
    {
        $this->connection->beginTransaction();
    }

    public function rollback()
    {
        $this->connection->rollBack();
    }

    private function execute(string $sql)
    {
        $this->statement = $this->connection->prepare($sql);
        $this->statement->execute($this->values);
        $this->values = [];
        return $this;
    }
}
