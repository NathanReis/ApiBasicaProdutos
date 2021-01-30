<?php

namespace Source\Models;

abstract class Model
{
    protected int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Model
    {
        $this->id = $id;

        return $this;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id
        ];
    }
}