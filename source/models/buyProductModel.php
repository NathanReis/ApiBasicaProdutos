<?php

namespace Source\Models;

use Source\Models\ProductModel;

class BuyProductModel
{
    private array $products = [];

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function addProduct(ProductModel $productModel): BuyProductModel
    {
        $this->products[] = $productModel;

        return $this;
    }

    public function toArray(): array
    {
        return array_map(function (ProductModel $productModel) {
                return $productModel->toArray();
            }, $this->products);
    }
}