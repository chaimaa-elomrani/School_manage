<?php

use App\Router;
use App\Middleware\CorsMiddleware;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Global CORS middleware
$router->globalMiddleware([CorsMiddleware::class]);

// API routes
$router->group(['prefix' => 'api'], function($router) {
    
    // Public routes
    $router->get('/', 'ApiController@index');
    $router->get('/health', 'ApiController@health');
    
    // Protected routes
    $router->group(['middleware' => [AuthMiddleware::class]], function($router) {
        $router->get('/users', 'UserController@index');
        $router->post('/users', 'UserController@store');
        $router->get('/users/{id}', 'UserController@show');
        $router->put('/users/{id}', 'UserController@update');
        $router->delete('/users/{id}', 'UserController@destroy');
    });
});

// Web routes
$router->get('/', function() {
    echo json_encode(['message' => 'Welcome to School Management System']);
});

$router->get('/docs', function() {
    echo json_encode([
        'title' => 'API Documentation',
        'version' => '1.0.0',
        'endpoints' => [
            'GET /' => 'Welcome message',
            'GET /api' => 'API information',
            'GET /api/health' => 'Health check',
            'GET /api/users' => 'List users (requires auth)',
            'POST /api/users' => 'Create user (requires auth)',
            'GET /api/users/{id}' => 'Get user (requires auth)',
            'PUT /api/users/{id}' => 'Update user (requires auth)',
            'DELETE /api/users/{id}' => 'Delete user (requires auth)'
        ]
    ]);
});

return $router;
