<?php

/**
 * Контроллер для управления задачами
 *
 * Обрабатывает HTTP запросы к API задач.
 * Реализует CRUD операции: создание, чтение, обновление, удаление.
 *
 * @package App\Controllers
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Controllers;

use App\DTO\TaskDTO;
use App\Services\TaskService;
use App\Exceptions\ValidationException;
use App\Exceptions\TaskNotFoundException;

class TaskController implements ControllerInterface
{
    /**
     * Сервис для работы с задачами
     *
     * @var TaskService
     */
    private TaskService $service;

    /**
     * Конструктор контроллера
     *
     * Инициализирует сервис задач.
     */
    public function __construct()
    {
        $this->service = new TaskService();
    }

    /**
     * Создает новую задачу
     *
     * @param array<string,string> $data Данные задачи из запроса
     * @return array<string,mixed> Ответ с кодом статуса и данными
     *
     * @example
     * POST /api/tasks
     * {
     *     "title": "Новая задача",
     *     "due_date": "2025-12-31T23:59:59",
     *     "priority": "высокий",
     *     "category": "Работа"
     * }
     */
    public function create(array $data): array
    {
        try {
            $dto = TaskDTO::fromArray($data);
            $task = $this->service->createTask($dto);

            return [
                'status' => 201,
                'data' => [
                    'id' => $task->getId(),
                    'message' => 'Task created successfully'
                ]
            ];
        } catch (ValidationException $e) {
            return [
                'status' => 400,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Получает список всех задач
     *
     * @param string|null $search Поиск по названию
     * @param string|null $sort Сортировка результатов
     * @return array<string,mixed> Ответ с кодом статуса и данными
     *
     * @example
     * GET /api/tasks?search=задача&sort=due_date
     */
    public function list(?string $search = null, ?string $sort = null): array
    {
        try {
            // получить задачи
            $tasks = $this->service->getTasks($search, $sort);
            // сформировать задачи из объектов в массив для вывода
            $result = array_map(fn($task) => $task->toArray(), $tasks);

            return [
                'status' => 200,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'data' => ['error' => 'Internal server error']
            ];
        }
    }

    /**
     * Получает задачу по ID
     *
     * @param int $id ID задачи
     * @return array<string,mixed> Ответ с кодом статуса и данными
     *
     * @example
     * GET /api/tasks/1
     */
    public function get(int $id): array
    {
        try {
            $task = $this->service->getTask($id);

            return [
                'status' => 200,
                'data' => $task->toArray()
            ];
        } catch (TaskNotFoundException $e) {
            return [
                'status' => 404,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Обновляет существующую задачу
     *
     * @param int $id ID задачи
     * @param array<string,string> $data Данные для обновления
     * @return array<string,mixed> Ответ с кодом статуса и данными
     *
     * @example
     * PUT /api/tasks/1
     * {
     *     "title": "Обновленная задача",
     *     "status": "выполнена"
     * }
     */
    public function update(int $id, array $data): array
    {
        try {
            $this->service->updateTask($id, $data);

            return [
                'status' => 200,
                'data' => ['message' => 'Task updated successfully']
            ];
        } catch (TaskNotFoundException $e) {
            return [
                'status' => 404,
                'data' => ['error' => $e->getMessage()]
            ];
        } catch (ValidationException $e) {
            return [
                'status' => 400,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }

    /**
     * Удаляет задачу
     *
     * @param int $id ID задачи для удаления
     * @return array<string,mixed> Ответ с кодом статуса и данными
     *
     * @example
     * DELETE /api/tasks/1
     */
    public function delete(int $id): array
    {
        try {
            $this->service->deleteTask($id);

            return [
                'status' => 200,
                'data' => ['message' => 'Task deleted successfully']
            ];
        } catch (TaskNotFoundException $e) {
            return [
                'status' => 404,
                'data' => ['error' => $e->getMessage()]
            ];
        }
    }
}
