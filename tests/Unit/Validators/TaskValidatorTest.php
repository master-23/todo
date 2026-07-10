<?php

/**
 * Unit-тесты для валидатора задач
 *
 * Проверяет все сценарии валидации данных задачи.
 *
 * @package Tests\Unit\Validators
 * @author Task Manager API
 * @version 1.0.0
 */

namespace Tests\Unit\Validators;

use PHPUnit\Framework\TestCase;
use App\Validators\TaskValidator;
use App\Exceptions\ValidationException;

class TaskValidatorTest extends TestCase
{
    /**
     * Экземпляр валидатора
     *
     * @var TaskValidator
     */
    private TaskValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new TaskValidator();
    }

    /**
     * Тест валидации корректных данных
     */
    public function testValidateWithValidData(): void
    {
        $data = [
            'title' => 'Valid Task',
            'due_date' => '2025-12-31 23:59:59',
            'priority' => 'высокий',
            'category' => 'Работа',
            'status' => 'не выполнена'
        ];

        $this->expectNotToPerformAssertions();
        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при отсутствии названия
     */
    public function testValidateWithMissingTitle(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Title is required and must be less than 255 characters');

        $data = [
            'due_date' => '2025-12-31 23:59:59',
            'priority' => 'высокий',
            'category' => 'Работа'
        ];

        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при слишком длинном названии
     */
    public function testValidateWithTooLongTitle(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Title is required and must be less than 255 characters');

        $data = [
            'title' => str_repeat('a', 256),
            'due_date' => '2025-12-31 23:59:59',
            'priority' => 'высокий',
            'category' => 'Работа'
        ];

        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при отсутствии срока выполнения
     */
    public function testValidateWithMissingDueDate(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Due date is required');

        $data = [
            'title' => 'Test Task',
            'priority' => 'высокий',
            'category' => 'Работа'
        ];

        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при неверном формате даты
     */
    public function testValidateWithInvalidDueDate(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid due date format');

        $data = [
            'title' => 'Test Task',
            'due_date' => 'invalid-date',
            'priority' => 'высокий',
            'category' => 'Работа'
        ];

        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при неверном приоритете
     */
    public function testValidateWithInvalidPriority(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid priority. Must be: низкий, средний, высокий');

        $data = [
            'title' => 'Test Task',
            'due_date' => '2025-12-31 23:59:59',
            'priority' => 'invalid',
            'category' => 'Работа'
        ];

        $this->validator->validate($data);
    }

    /**
     * Тест ошибки при отсутствии категории
     */
    public function testValidateWithMissingCategory(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Category is required');

        $data = [
            'title' => 'Test Task',
            'due_date' => '2025-12-31 23:59:59',
            'priority' => 'высокий'
        ];

        $this->validator->validate($data);
    }
}
