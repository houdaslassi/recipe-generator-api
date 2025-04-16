<?php

use DI\Container;
use Slim\Factory\AppFactory;
use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as RequestInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandlerInterface;

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Create Container
$container = new Container();
AppFactory::setContainer($container);

// Create App
$app = AppFactory::create();

// Add Error Middleware
$app->addErrorMiddleware(true, true, true);

// Add CORS Middleware
$app->add(function (RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Add JSON Parsing Middleware
$app->addBodyParsingMiddleware();

// Routes
$app->get('/', function (RequestInterface $request, ResponseInterface $response) {
    $response->getBody()->write(json_encode([
        'message' => 'Welcome to the Recipe Generator API',
        'version' => '1.0.0'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->group('/api/recipes', function ($group) {
    $group->get('', \App\Controllers\RecipeController::class . ':index');
    $group->get('/{id}', \App\Controllers\RecipeController::class . ':show');
    $group->post('', \App\Controllers\RecipeController::class . ':create');
    $group->put('/{id}', \App\Controllers\RecipeController::class . ':update');
    $group->delete('/{id}', \App\Controllers\RecipeController::class . ':delete');
    $group->post('/generate', \App\Controllers\RecipeGeneratorController::class . ':generate');
});

return $app; 