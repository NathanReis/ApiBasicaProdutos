<?php

namespace Source\Validations;

use Source\DAOs\ProductDAO;
use Source\Models\{
    BuyProductModel,
    Model
};

class ProductValidation extends Validation
{
    public function __construct(ProductDAO $dao)
    {
        $this->dao = $dao;
    }

    public function checkBuy(BuyProductModel $model): array
    {
        $errors = [];

        foreach ($model->getProducts() as $productModel) {
            $errorsAux = $this->getErrorsId($productModel->getId());

            if (empty($errorsAux)) {
                $byId = $this->dao->findFirst("id", $productModel->getId());

                $errorsAux = array_merge_recursive($errorsAux, $this->getErrorsAmount($productModel->getAmount()));

                $totalProducts = (int)$byId->amount + $productModel->getAmount();
                $extraAmount = $totalProducts - 255;

                if ($extraAmount > 0) {
                    $errorsAux["amount"][] = "Limite máximo do estoque ultrapassado em {$extraAmount}";
                }
            }

            if (!empty($errorsAux)) {
                $errors[$productModel->getId()] = $errorsAux;
            }
        }

        return $errors;
    }

    public function checkCreate(Model $model): array
    {
        $errors = $this->getErrorsAmount($model->getAmount());
        $errors = array_merge_recursive($errors, $this->getErrorsName($model->getName(), $model->getId()));
        $errors = array_merge_recursive($errors, $this->getErrorsPrice($model->getPrice()));

        return $errors;
    }

    public function checkSell(BuyProductModel $model): array
    {
        $errors = [];

        foreach ($model->getProducts() as $productModel) {
            $errorsAux = $this->getErrorsId($productModel->getId());

            if (empty($errorsAux)) {
                $byId = $this->dao->findFirst("id", $productModel->getId());

                $errorsAux = array_merge_recursive($errorsAux, $this->getErrorsAmount($productModel->getAmount()));

                $totalProducts = (int)$byId->amount - $productModel->getAmount();
                $amountMissing = 0 - $totalProducts;

                if ($amountMissing > 0) {
                    $errorsAux["amount"][] = "Limite mínimo do estoque ultrapassado em {$amountMissing}";
                }
            }

            if (!empty($errorsAux)) {
                $errors[$productModel->getId()] = $errorsAux;
            }
        }

        return $errors;
    }

    public function checkUpdate(Model $model): array
    {
        $errors = $this->getErrorsId($model->getId());

        if (empty($errors)) {
            $errors = array_merge_recursive($errors, $this->getErrorsAmount($model->getAmount()));
            $errors = array_merge_recursive($errors, $this->getErrorsName($model->getName(), $model->getId()));
            $errors = array_merge_recursive($errors, $this->getErrorsPrice($model->getPrice()));
        }

        return $errors;
    }

    private function getErrorsAmount(int $amount): array
    {
        $errors = [];

        if ($amount < 0 || $amount > 255) {
            $errors["amount"][] = "Deve ser de 0 a 255";
        }

        return $errors;
    }

    private function getErrorsName(string $name, int $id): array
    {
        $errors = [];

        if (empty($name)) {
            $errors["name"][] = "Não pode ser vazio";
        } else if (mb_strlen($name) > 30) {
            $errors["name"][] = "Não pode ter mais de 30 caracteres";
        } else {
            $byName = $this->dao->findFirst("name", $name);

            if (!empty($byName) && $byName->id != $id) {
                $errors["name"][] = "Valor já existente";
            }
        }

        return $errors;
    }

    private function getErrorsPrice(int $price): array
    {
        $errors = [];

        if ($price < 0.00 || $price > 9999.99) {
            $errors["price"][] = "Deve ser de R$ 0,00 a R$ 9.999,00";
        }

        return $errors;
    }
}