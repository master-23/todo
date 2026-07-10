<?php

/**
 * Unit-тесты для сущности Task
 *
 * Проверяет создание, геттеры, сеттеры и преобразование в массив.
 *
 * @package Tests\Unit\Entities
 * @author Task Manager API
 * @version 1.0.0
 */

namespace Tests\Unit\Entities;

use PHPUnit\Framework\TestCase;
use DateTime;
use App\Entities\Task;
use App\Enums\Priority;
use App\Enums\Status;

class TaskTest extends TestCase
{
    /**
     * Экземпляр задачи для тестирования
     *
     * @var Task
     */
    private Task $task;

    /**
     * Подготовка перед каждым тестом
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->task = new Task(
            'Test Task',
            new DateTime('2025-12-31 23:59:59'),
            Priority::HIGH,
            'Работа',
            'Test Description',
            Status::PENDING,
            1,
            new DateTime('2025-01-01 00:00:00')
        );
    }

    /**
     * Тест создания задачи
     *
     * Проверяет все поля после создания.
     */
    public function testTaskCreation(): void
    {
        $this->assertEquals(1, $this->task->getId());
        $this->assertEquals('Test Task', $this->task->getTitle());
        $this->assertEquals('Test Description', $this->task->getDescription());
        $this->assertEquals('2025-12-31 23:59:59', $this->task->getDueDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('2025-01-01 00:00:00', $this->task->getCreatedAt()->format('Y-m-d H:i:s'));
        $this->assertEquals(Status::PENDING, $this->task->getStatus());
        $this->assertEquals(Priority::HIGH, $this->task->getPriority());
        $this->assertEquals('Работа', $this->task->getCategory());
    }

    /**
     * Тест изменения названия
     */
    public function testSetTitle(): void
    {
        $this->task->setTitle('Updated Task');
        $this->assertEquals('Updated Task', $this->task->getTitle());
    }

    /**
     * Тест изменения описания
     */
    public function testSetDescription(): void
    {
        $this->task->setDescription('Updated Description');
        $this->assertEquals('Updated Description', $this->task->getDescription());
    }

    /**
     * Тест изменения срока выполнения
     */
    public function testSetDueDate(): void
    {
        $newDate = new DateTime('2026-01-01 00:00:00');
        $this->task->setDueDate($newDate);
        $this->assertEquals('2026-01-01 00:00:00', $this->task->getDueDate()->format('Y-m-d H:i:s'));
    }

    /**
     * Тест изменения статуса
     */
    public function testSetStatus(): void
    {
        $this->task->setStatus(Status::COMPLETED);
        $this->assertEquals(Status::COMPLETED, $this->task->getStatus());
    }

    /**
     * Тест изменения приоритета
     */
    public function testSetPriority(): void
    {
        $this->task->setPriority(Priority::LOW);
        $this->assertEquals(Priority::LOW, $this->task->getPriority());
    }

    /**
     * Тест преобразования в массив
     */
    public function testToArray(): void
    {
        $array = $this->task->toArray();

        $this->assertIsArray($array);
        $this->assertEquals(1, $array['id']);
        $this->assertEquals('Test Task', $array['title']);
        $this->assertEquals('Test Description', $array['description']);
        $this->assertEquals('2025-12-31 23:59:59', $array['due_date']);
        $this->assertEquals('2025-01-01 00:00:00', $array['created_at']);
        $this->assertEquals('не выполнена', $array['status']);
        $this->assertEquals('высокий', $array['priority']);
        $this->assertEquals('Работа', $array['category']);
    }
}
