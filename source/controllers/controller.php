<?php

namespace Source\Controllers;

use Exception;
use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};
use Source\DAOs\DAO;
use Source\Models\{
    Model,
    ResponseModel
};
use Source\Validations\Validation;

abstract class Controller
{
    protected DAO $dao;
    protected Validation $validation;

    abstract protected function fillModel(array $data): Model;

    public function create(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $startedTransaction = false;
        $statusCode = 0;
        $success = false;

        try {
            $model = $this->fillModel($request->getParsedBody());
            $model->setId(0);

            $errors = $this->validation->checkCreate($model);

            if ($errors) {
                $dataResponse = $errors;
                $statusCode = 400;
            } else {
                DAO::startTransaction();

                $startedTransaction = true;

                $this->dao->create($model);

                DAO::commitTransaction();

                $dataResponse = [
                    "id" => $model->getId()
                ];
                $statusCode = 201;
                $success = true;
            }
        } catch (Exception $exception) {
            if ($startedTransaction) {
                DAO::rollBackTransaction();
            }

            $dataResponse = [
                $exception->getMessage()
            ];
            $statusCode = 500;
        }

        return $this->getNewResponse($response, $dataResponse, $statusCode, $success);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $statusCode = 0;
        $success = false;

        try {
            $id = (int)$args["id"];

            DAO::startTransaction();

            $this->dao->delete($id);

            DAO::commitTransaction();

            $statusCode = 200;
            $success = true;
        } catch (Exception $exception) {
            $dataResponse = [
                $exception->getMessage()
            ];
            $statusCode = 500;
        }

        return $this->getNewResponse($response, $dataResponse, $statusCode, $success);
    }

    final protected function fillResponseModel(array $data, int $statusCode, bool $success): ResponseModel
    {
        return (new ResponseModel())
            ->setData($data)
            ->setStatusCode($statusCode)
            ->setSuccess($success);
    }

    public function findAll(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $statusCode = 0;
        $success = false;

        try {
            $dataResponse = $this->dao->findAll();
            $statusCode = 200;
            $success = true;
        } catch (Exception $exception) {
            $dataResponse = [
                $exception->getMessage()
            ];
            $statusCode = 500;
        }

        return $this->getNewResponse($response, $dataResponse, $statusCode, $success);
    }

    public function findFirst(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $statusCode = 0;
        $success = false;

        try {
            $id = (int)$args["id"];

            $dataResponse[] = $this->dao->findFirst("id", $id);
            $statusCode = 200;
            $success = true;
        } catch (Exception $exception) {
            $dataResponse = [
                $exception->getMessage()
            ];
            $statusCode = 500;
        }

        return $this->getNewResponse($response, $dataResponse, $statusCode, $success);
    }

    final protected function getNewResponse(Response $response, array $data, int $statusCode, bool $success): Response
    {
        $response
            ->getBody()
            ->write(
                $this
                    ->fillResponseModel($data, $statusCode, $success)
                    ->toJSON()
            );

        return $response
            ->withHeader("Content-Type", "application/json; charset=UTF-8")
            ->withStatus($statusCode);
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $startedTransaction = false;
        $statusCode = 0;
        $success = false;

        try {
            $model = $this->fillModel($request->getParsedBody());
            $model->setId((int)$args["id"]);

            $errors = $this->validation->checkUpdate($model);

            if ($errors) {
                $dataResponse = $errors;
                $statusCode = 400;
            } else {
                DAO::startTransaction();

                $startedTransaction = true;

                $this->dao->update($model);

                DAO::commitTransaction();

                $statusCode = 200;
                $success = true;
            }
        } catch (Exception $exception) {
            if ($startedTransaction) {
                DAO::rollBackTransaction();
            }

            $dataResponse = [
                $exception->getMessage()
            ];
            $statusCode = 500;
        }

        return $this->getNewResponse($response, $dataResponse, $statusCode, $success);
    }
}