<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use App\Repositories\SensorRepository;

class Sensoren
{
    public function __construct(private SensorRepository $repository)
    {
        
    }

    public function showAllSensoren(Request $request, Response $response): Response
    {
        $data = $this->repository->getAllSensoren();
    
        $body = json_encode($data);
    
        $response->getBody()->write($body);
        return $response->withHeader('Content-Type', 'application/json');  
    }
}