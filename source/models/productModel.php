<?php

namespace Source\Models;

use Source\Models\Model;

class ProductModel extends Model
{
    private int $amount = 0;
    private string $name = "";
    private float $price = 0.0;

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): ProductModel
    {
        $this->amount = $amount;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): ProductModel
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): ProductModel
    {
        $this->price = $price;

        return $this;
    }

    public function toArray(): array
    {
        $asArray = parent::toArray();

        $asArray["amount"] = $this->amount;
        $asArray["name"] = $this->name;
        $asArray["price"] = $this->price;

        return $asArray;
    }
}