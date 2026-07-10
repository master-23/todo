<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Core\Router;
use App\Core\Request;
use App\Controllers\TaskController;

// use App\Repositories\TaskRepository;
// use Tests\Integration\Repository\TaskRepositoryTest;

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
//load .env
$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();
$request = new Request();

$router->addRoute('GET', '/api/tasks', TaskController::class, 'list');
$router->addRoute('POST', '/api/tasks', TaskController::class, 'create');
$router->addRoute('GET', '/api/tasks/{id}', TaskController::class, 'get');
$router->addRoute('PUT', '/api/tasks/{id}', TaskController::class, 'update');
$router->addRoute('DELETE', '/api/tasks/{id}', TaskController::class, 'delete');

$response = $router->dispatch($request);

// установить код возврата страницы
http_response_code($response['status']);

// вернуть содержимое ответа в json формате
echo json_encode($response['data']);
