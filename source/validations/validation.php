<?php

namespace Source\Validations;

use Source\DAOs\DAO;
use Source\Models\Model;

abstract class Validation
{
    protected DAO $dao;

    public function checkCreate(Model $model): array
    {
        return [
            "This method didn't implemented"
        ];
    }

    public function checkUpdate(Model $model): array
    {
        return [
            "This method didn't implemented"
        ];
    }

    protected function getErrorsId(int $id): array
    {
        $errors = [];

        $byId = $this->dao->findFirst("id", $id);

        if (empty($byId)) {
            $errors["id"][] = "ID não encontrado";
        }

        return $errors;
    }
}