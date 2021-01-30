<?php

use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;

require_once implode(DIRECTORY_SEPARATOR, [__DIR__, "..", "vendor", "autoload.php"]);

$app = AppFactory::create();
$app->addBodyParsingMiddleware();
$app->addErrorMiddleware(true, true, true);

$app->group("/products", function (RouteCollectorProxy $group) {
    $classTarget = "Source\\Controllers\\ProductController";

    $group->delete("/{id:\d+}", "{$classTarget}:delete");

    $group->get("", "{$classTarget}:findAll");
    
    $group->get("/{id:\d+}", "{$classTarget}:findFirst");
    
    $group->post("", "{$classTarget}:create");
    
    $group->put("/{id:\d+}", "{$classTarget}:update");
});

$app->put("/buy-products", "Source\\Controllers\\ProductController:buy");

$app->put("/sell-products", "Source\\Controllers\\ProductController:sell");

$app->run();