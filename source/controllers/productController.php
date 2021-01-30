<?php

namespace Source\Controllers;

use Exception;
use Psr\Http\Message\{
    ResponseInterface as Response,
    ServerRequestInterface as Request
};
use Source\Controllers\Controller;
use Source\DAOs\{
    DAO,
    ProductDAO
};
use Source\Models\{
    BuyProductModel,
    Model,
    ProductModel
};
use Source\Validations\ProductValidation;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->dao = new ProductDAO();
        $this->validation = new ProductValidation($this->dao);
    }

    public function buy(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $startedTransaction = false;
        $statusCode = 0;
        $success = false;

        try {
            $model = $this->fillBuyProductModel($request->getParsedBody());

            $errors = $this->validation->checkBuy($model);

            if ($errors) {
                $dataResponse = $errors;
                $statusCode = 400;
            } else {
                DAO::startTransaction();

                $startedTransaction = true;

                $this->dao->buy($model);

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

    private function fillBuyProductModel(array $data): BuyProductModel
    {
        $model = new BuyProductModel();

        foreach ($data as $product) {
            $model->addProduct(
                (new ProductModel())
                    ->setAmount((int)$product["amount"])
                    ->setId((int)$product["id"])
            );
        }

        return $model;
    }

    protected function fillModel(array $data): Model
    {
        return (new ProductModel())
            ->setAmount((int)$data["amount"])
            ->setName(trim((string)$data["name"]))
            ->setPrice(round((float)$data["price"], 2));
    }

    public function sell(Request $request, Response $response, array $args): Response
    {
        $dataResponse = [];
        $startedTransaction = false;
        $statusCode = 0;
        $success = false;

        try {
            $model = $this->fillBuyProductModel($request->getParsedBody());

            $errors = $this->validation->checkSell($model);

            if ($errors) {
                $dataResponse = $errors;
                $statusCode = 400;
            } else {
                DAO::startTransaction();

                $startedTransaction = true;

                $this->dao->sell($model);

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