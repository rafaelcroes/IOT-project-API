<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require dirname(__DIR__) . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/api/sensoren', function (Request $request, Response $response) {

    $database = new App\Database;

    $repository = new App\Repositories\SensorRepository($database);

    $data = $repository->getAllSensoren();

    $body = json_encode($data);

    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');  
});

$app->run();