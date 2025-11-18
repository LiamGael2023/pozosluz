<?php
namespace App\Core;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return Database::query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = Database::query($sql, [$id])->fetch();
        return $result ?: null;
    }

    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        Database::query($sql, array_values($data));

        return (int) Database::getInstance()->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?";

        $values = array_values($data);
        $values[] = $id;

        return Database::query($sql, $values)->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return Database::query($sql, [$id])->rowCount() > 0;
    }

    public function where(string $column, $value): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        return Database::query($sql, [$value])->fetchAll();
    }
}
