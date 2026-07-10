<?php

/**
 * Маршрутизатор
 *
 * Обрабатывает входящие HTTP запросы и направляет их к соответствующим контроллерам.
 * Поддерживает динамические маршруты с параметрами.
 *
 * @package App\Core
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Core;

use Reflection;
use ReflectionMethod;
use ReflectionParameter;

class Router
{
    /**
     * Зарегистрированные маршруты
     *
     * @var array<mixed>
     */
    private array $routes = [];

    /**
     * Добавляет новый маршрут
     *
     * @param string $method HTTP метод (GET, POST, PUT, DELETE)
     * @param string $path URL путь с возможными параметрами (например, /api/tasks/{id})
     * @param string $controller Класс контроллера
     * @param string $action Метод контроллера
     * @return void
     *
     * @example
     * $router->addRoute('GET', '/api/tasks/{id}', TaskController::class, 'get');
     */
    public function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[$method][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Обрабатывает запрос и возвращает ответ
     *
     * @param Request $request Объект запроса
     * @return array<string,mixed> Ответ с кодом статуса и данными
     */
    public function dispatch(Request $request): array
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Удаляем строку запроса из пути
        $path = (string)strtok($path, '?');

        // Проверяем точное совпадение
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            return $this->execute($route['controller'], $route['action'], $request);
        }

        // Проверяем маршруты с параметром {id}
        foreach ($this->routes[$method] as $routePath => $route) {
            $pattern = preg_replace('/\{id\}/', '(\d+)', $routePath);
            if (preg_match('#^' . $pattern . '$#', $path, $matches)) {
                $request->setParam('id', (int)$matches[1]);
                return $this->execute($route['controller'], $route['action'], $request);
            }
        }

        // Маршрут не найден
        return [
            'status' => 404,
            'data' => ['error' => 'Route not found']
        ];
    }

    /**
     * Выполняет действие контроллера
     *
     * @param string $controllerClass Класс контроллера
     * @param string $action Метод контроллера
     * @param Request $request Объект запроса
     * @return array<string,mixed> Результат выполнения
     */
    private function execute(string $controllerClass, string $action, Request $request): array
    {
        $controller = new $controllerClass();

        // Проверяем существование метода
        if (!method_exists($controller, $action)) {
            return [
                'status' => 500,
                'data' => ['error' => 'Invalid action']
            ];
        }

        if ($action === 'list') {
            $search = $request->getParam('search');
            $sort = $request->getParam('sort');
            return $controller->list($search, $sort);
        }

        if ($action === 'create') {
            return $controller->create($request->getBody());
        }

        if ($action === 'get' || $action === 'update' || $action === 'delete') {
            $id = $request->getParam('id');
            if ($action === 'update') {
                return $controller->update($id, $request->getBody());
            }
            return $controller->$action($id);
        }

        return [
            'status' => 500,
            'data' => ['error' => 'Invalid action']
        ];
    }
}
