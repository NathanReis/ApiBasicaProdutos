<?php

namespace Source\DAOs;

use Exception;
use Source\DAOs\Connection;
use Source\Models\Model;

abstract class DAO
{
    protected $table;

    public static function commitTransaction(): void
    {
        Connection::getInstance()->commit();
    }

    public function create(Model $model): void
    {
        throw new Exception("This method didn't implemented");
    }

    public function delete(int $id): void
    {
        $sql = "DELETE FROM `{$this->table}` WHERE `id` = {$id};";

        Connection::getInstance()
            ->prepare($sql)
            ->execute();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY `name`, `amount`, `price`;";

        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findFirst(string $field, $value): ?object
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE `{$field}` = :{$field};";

        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute([
            $field => $value
        ]);

        return $stmt->fetch() ?: null;
    }

    protected function getInsertedId(): int
    {
        $sql = "SELECT LAST_INSERT_ROWID() AS `id` FROM `{$this->table}`;";

        $stmt = Connection::getInstance()->prepare($sql);
        $stmt->execute();

        return $stmt->fetch()->id;
    }

    public static function rollBackTransaction(): void
    {
        Connection::getInstance()->rollBack();
    }
  
    public static function startTransaction(): void
    {
        Connection::getInstance()->beginTransaction();
    }

    public function update(Model $model): void
    {
        throw new Exception("This method didn't implemented");
    }
}