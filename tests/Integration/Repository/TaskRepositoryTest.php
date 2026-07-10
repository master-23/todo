<?php

/**
 * Интеграционные тесты для репозитория задач
 *
 * Проверяет взаимодействие с реальной базой данных.
 *
 * @package Tests\Integration\Repository
 * @author Task Manager API
 * @version 1.0.0
 */

namespace Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use DateTime;
use App\Repositories\TaskRepository;
use App\Entities\Task;
use App\Enums\Priority;
use App\Enums\Status;
use App\Config\Database;

class TaskRepositoryTest extends TestCase
{
    /**
     * Экземпляр репозитория
     *
     * @var TaskRepository
     */
    private TaskRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $db = Database::getConnection();
        $db->exec("TRUNCATE TABLE tasks");

        $this->repository = new TaskRepository();
    }

    /**
     * Тест создания задачи
     */
    public function testSaveInsert(): void
    {
        $task = new Task(
            'Test Task',
            new DateTime('2025-12-31 23:59:59'),
            Priority::HIGH,
            'Работа',
            'Test Description',
            Status::PENDING
        );

        $savedTask = $this->repository->save($task);

        $this->assertNotNull($savedTask->getId());
        $this->assertEquals('Test Task', $savedTask->getTitle());
        $this->assertEquals('Test Description', $savedTask->getDescription());
        $this->assertEquals('2025-12-31 23:59:59', $savedTask->getDueDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(Priority::HIGH, $savedTask->getPriority());
        $this->assertEquals('Работа', $savedTask->getCategory());
        $this->assertEquals(Status::PENDING, $savedTask->getStatus());
    }

    /**
     * Тест поиска задачи по ID
     */
    public function testFindById(): void
    {
        $task = new Task(
            'Test Task',
            new DateTime('2025-12-31 23:59:59'),
            Priority::MEDIUM,
            'Личное'
        );
        $savedTask = $this->repository->save($task);

        $foundTask = $this->repository->findById((int)$savedTask->getId());

        $this->assertNotNull($foundTask);
        $this->assertEquals($savedTask->getId(), $foundTask->getId());
        $this->assertEquals('Test Task', $foundTask->getTitle());
    }

    /**
     * Тест поиска несуществующей задачи
     */
    public function testFindByIdNotFound(): void
    {
        $task = $this->repository->findById(999);
        $this->assertNull($task);
    }

    /**
     * Тест обновления задачи
     */
    public function testSaveUpdate(): void
    {
        $task = new Task(
            'Original Task',
            new DateTime('2025-12-31 23:59:59'),
            Priority::LOW,
            'Работа'
        );
        $savedTask = $this->repository->save($task);

        $savedTask->setTitle('Updated Task');
        $savedTask->setPriority(Priority::HIGH);
        $savedTask->setStatus(Status::COMPLETED);

        $updatedTask = $this->repository->save($savedTask);

        $this->assertEquals('Updated Task', $updatedTask->getTitle());
        $this->assertEquals(Priority::HIGH, $updatedTask->getPriority());
        $this->assertEquals(Status::COMPLETED, $updatedTask->getStatus());
    }

    /**
     * Тест удаления задачи
     */
    public function testDelete(): void
    {
        $task = new Task(
            'Task to Delete',
            new DateTime('2025-12-31 23:59:59'),
            Priority::MEDIUM,
            'Работа'
        );
        $savedTask = $this->repository->save($task);

        $result = $this->repository->delete((int)$savedTask->getId());

        $this->assertTrue($result);

        $foundTask = $this->repository->findById((int)$savedTask->getId());
        $this->assertNull($foundTask);
    }
}
