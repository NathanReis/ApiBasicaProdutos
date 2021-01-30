<?php

namespace Source\DAOs;

use Source\DAOs\{
    Connection,
    DAO
};
use Source\Models\{
    BuyProductModel,
    Model
};

class ProductDAO extends DAO
{
    public function __construct()
    {
        $this->table = "tbProducts";
    }

    public function buy(BuyProductModel $model): void
    {
        $sql  = "UPDATE `{$this->table}` ";
        $sql .= "SET `amount` = (`amount` + :amount) ";
        $sql .= "WHERE `id` = :id;";

        $stmt = Connection::getInstance()->prepare($sql);

        foreach ($model->getProducts() as $productModel) {
            $stmt->execute([
                "amount" => $productModel->getAmount(),
                "id" => $productModel->getId()
            ]);
        }
    }

    public function create(Model $model): void
    {
        $sql  = "INSERT INTO `{$this->table}` (`amount`, `name`, `price`) ";
        $sql .= "VALUES (:amount, :name, :price);";

        Connection::getInstance()
            ->prepare($sql)
            ->execute([
                "amount" => $model->getAmount(),
                "name" => $model->getName(),
                "price" => $model->getPrice()
            ]);

        $model->setId($this->getInsertedId());
    }

    public function sell(BuyProductModel $model): void
    {
        $sql  = "UPDATE `{$this->table}` ";
        $sql .= "SET `amount` = (`amount` - :amount) ";
        $sql .= "WHERE `id` = :id;";

        $stmt = Connection::getInstance()->prepare($sql);

        foreach ($model->getProducts() as $productModel) {
            $stmt->execute([
                "amount" => $productModel->getAmount(),
                "id" => $productModel->getId()
            ]);
        }
    }

    public function update(Model $model): void
    {
        $sql  = "UPDATE `{$this->table}` ";
        $sql .= "SET ";
        $sql .= "    `amount` = :amount, ";
        $sql .= "    `name` = :name, ";
        $sql .= "    `price` = :price ";
        $sql .= "WHERE `id` = :id;";

        Connection::getInstance()
            ->prepare($sql)
            ->execute([
                "amount" => $model->getAmount(),
                "name" => $model->getName(),
                "price" => $model->getPrice(),
                "id" => $model->getId()
            ]);
    }
}