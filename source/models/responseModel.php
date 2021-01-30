<?php

namespace Source\Models;

class ResponseModel
{
    private array $data = [];
    private int $statusCode = 0;
    private bool $success = false;

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): ResponseModel
    {
        $this->data = $data;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): ResponseModel
    {
        $this->statusCode = $statusCode;
        
        return $this;
    }

    public function getSuccess(): bool
    {
        return $this->success;
    }

    public function setSuccess(bool $success): ResponseModel
    {
        $this->success = $success;
        
        return $this;
    }

    public function toArray(): array
    {
        return [
            "data" => $this->data,
            "statusCode" => $this->statusCode,
            "success" => $this->success
        ];
    }

    public function toJSON(): string
    {
        return json_encode($this->toArray());
    }
}