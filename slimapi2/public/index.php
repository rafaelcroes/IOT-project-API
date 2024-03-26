<?php
//wawa
declare(strict_types=1);

use App\Middleware\AapSleutel;
use Slim\Factory\AppFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use DI\Container;
use DI\ContainerBuilder;
use Slim\Handlers\Strategies\RequestResponseArgs;
use Slim\Psr7\Message;

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/vendor/autoload.php';

$builder = new ContainerBuilder();



$container = $builder->addDefinitions(APP_ROOT . '/config/definitions.php')
                     ->build();


AppFactory::setContainer($container);

$app = AppFactory::create();

$collector =$app->getRouteCollector();
$collector->setDefaultInvocationStrategy(new RequestResponseArgs);

$error_middleware = $app->addErrorMiddleware(true, true, true);

$error_handler = $error_middleware->getDefaultErrorHandler();

$error_handler->forceContentType('application/json');

$app->add(new AapSleutel);

$app->get('/api/sensoren', App\Controllers\Sensoren::class . ':showAllSensoren');


$app->get('/api/metingen', function (Request $request, Response $response) {

    $repository = $this->get(App\Repositories\MetingRepository::class);

    $data = $repository->getAllMetingen();

    $body = json_encode($data);

    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');  
});

$app->get('/api/sensoren/{id:[0-9]+}', function (Request $request, Response $response, string $id){   

    $repository = $this->get(App\Repositories\SensorRepository::class);

    $data = $repository->getSensorById((int) $id);

    if($data === false)
    {
        throw new \Slim\Exception\HttpNotFoundException($request, 
                                                        message: "Sensor bestaat niet");
    }

    $body = json_encode($data);

    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');  

});

$app->get('/api/metingen/{id:[0-9]+}', function (Request $request, Response $response, string $id){   

    $repository = $this->get(App\Repositories\MetingRepository::class);

    $data = $repository->getMetingBySensorId((int) $id);

    if($data === false)
    {
        throw new \Slim\Exception\HttpNotFoundException($request, 
                                                        message: "Sensor bestaat niewt");
    }

    $body = json_encode($data);

    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');  

});


$app->run();
