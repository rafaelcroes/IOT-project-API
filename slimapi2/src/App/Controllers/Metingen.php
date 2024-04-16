<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\MetingRepository;

class Metingen
{
    public function __construct(private MetingRepository $repository)
    {
        
    }

    public function getAllMetingen(Request $request, Response $response): Response
    {
        $data = $this->repository->getAllMetingen();
    
        $body = json_encode($data);
    
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');  

    }

    public function getMetingBySensorId(Request $request, Response $response, string $id): Response
    {

    $data = $this->repository->getMetingBySensorId((int) $id);


    if($data === false)
    {
        throw new \Slim\Exception\HttpNotFoundException($request, 
                                                        message: "Sensor bestaat niewt");
    }

    $body = json_encode($data);

    $response->getBody()->write($body);
    return $response->withHeader('Content-Type', 'application/json');  

    }
}