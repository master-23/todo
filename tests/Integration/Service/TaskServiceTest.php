<?php

/**
 * Интеграционные тесты для сервиса задач
 *
 * Проверяет бизнес-логику в сервисном слое.
 *
 * @package Tests\Integration\Service
 * @author Task Manager API
 * @version 1.0.0
 */

namespace Tests\Integration\Service;

use PHPUnit\Framework\TestCase;
use DateTime;
use App\Services\TaskService;
use App\DTO\TaskDTO;
use App\Enums\Priority;
use App\Enums\Status;
use App\Exceptions\TaskNotFoundException;
use App\Config\Database;

class TaskServiceTest extends TestCase
{
    /**
     * Экземпляр сервиса
     *
     * @var TaskService
     */
    private TaskService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $db = Database::getConnection();
        $db->exec("TRUNCATE TABLE tasks");

        $this->service = new TaskService();
    }

    /**
     * Тест создания задачи
     */
    public function testCreateTask(): void
    {
        $dto = new TaskDTO(
            'Test Task',
            'Test Description',
            new DateTime('2025-12-31 23:59:59'),
            new DateTime('2025-01-01 00:00:00'),
            Priority::HIGH,
            'Работа',
            Status::PENDING
        );

        $task = $this->service->createTask($dto);

        $this->assertNotNull($task->getId());
        $this->assertEquals('Test Task', $task->getTitle());
        $this->assertEquals('Test Description', $task->getDescription());
        $this->assertEquals(Priority::HIGH, $task->getPriority());
        $this->assertEquals('Работа', $task->getCategory());
        $this->assertEquals(Status::PENDING, $task->getStatus());
    }

    /**
     * Тест получения задачи по ID
     */
    public function testGetTask(): void
    {
        $dto = new TaskDTO(
            'Test Task',
            null,
            new DateTime('2025-12-31 23:59:59'),
            new DateTime('2025-01-01 00:00:00'),
            Priority::MEDIUM,
            'Личное',
            Status::PENDING
        );
        $createdTask = $this->service->createTask($dto);

        $task = $this->service->getTask((int)$createdTask->getId());

        $this->assertEquals($createdTask->getId(), $task->getId());
        $this->assertEquals('Test Task', $task->getTitle());
    }

    /**
     * Тест получения несуществующей задачи
     */
    public function testGetTaskNotFound(): void
    {
        $this->expectException(TaskNotFoundException::class);
        $this->expectExceptionMessage('Task with ID 999 not found');

        $this->service->getTask(999);
    }

    /**
     * Тест обновления задачи
     */
    public function testUpdateTask(): void
    {
        $dto = new TaskDTO(
            'Original Task',
            'Original Description',
            new DateTime('2025-12-31 23:59:59'),
            new DateTime('2025-01-01 00:00:00'),
            Priority::LOW,
            'Работа',
            Status::PENDING
        );
        $createdTask = $this->service->createTask($dto);

        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'priority' => 'высокий',
            'status' => 'выполнена',
            'category' => 'Личное'
        ];

        $updatedTask = $this->service->updateTask((int)$createdTask->getId(), $updateData);

        $this->assertEquals('Updated Task', $updatedTask->getTitle());
        $this->assertEquals('Updated Description', $updatedTask->getDescription());
        $this->assertEquals(Priority::HIGH, $updatedTask->getPriority());
        $this->assertEquals(Status::COMPLETED, $updatedTask->getStatus());
        $this->assertEquals('Личное', $updatedTask->getCategory());
    }

    /**
     * Тест удаления задачи
     */
    public function testDeleteTask(): void
    {
        $dto = new TaskDTO(
            'Task to Delete',
            null,
            new DateTime('2025-12-31 23:59:59'),
            new DateTime('2025-01-01 00:00:00'),
            Priority::MEDIUM,
            'Работа',
            Status::PENDING
        );
        $createdTask = $this->service->createTask($dto);

        $this->service->deleteTask((int)$createdTask->getId());

        $this->expectException(TaskNotFoundException::class);
        $this->service->getTask((int)$createdTask->getId());
    }
}
