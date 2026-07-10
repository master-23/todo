<?php

/**
 * Валидатор данных задачи
 *
 * Отвечает за проверку корректности данных перед сохранением.
 * Проверяет обязательные поля, форматы данных и допустимые значения.
 *
 * @package App\Validators
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Validators;

use App\Enums\Priority;
use App\Enums\Status;
use App\Exceptions\ValidationException;

class TaskValidator implements ValidatorInterface
{
    /**
     * Проверяет валидность данных задачи
     *
     * Выполняет все необходимые проверки:
     * - Название: обязательно, не более 255 символов
     * - Срок выполнения: обязателен, корректный формат даты
     * - Приоритет: обязателен, допустимое значение
     * - Категория: обязательна
     * - Статус: опционален, допустимое значение
     *
     * @param array<string,mixed> $data Данные для проверки
     * @throws ValidationException Если данные не прошли проверку
     *
     * @example
     * $validator = new TaskValidator();
     * try {
     *     $validator->validate($requestData);
     * } catch (ValidationException $e) {
     *     echo $e->getMessage();
     * }
     */
    public function validate(array $data): void
    {
        // Проверка названия
        if (empty($data['title']) || strlen($data['title']) > 255) {
            throw new ValidationException('Title is required and must be less than 255 characters');
        }

        // Проверка срока выполнения
        if (empty($data['due_date'])) {
            throw new ValidationException('Due date is required');
        }

        // Проверка формата даты
        try {
            new \DateTime($data['due_date']);
        } catch (\Exception $e) {
            throw new ValidationException('Invalid due date format');
        }

        // Проверка приоритета
        if (empty($data['priority']) || !Priority::isValid($data['priority'])) {
            throw new ValidationException('Invalid priority. Must be: низкий, средний, высокий');
        }

        // Проверка категории
        if (empty($data['category'])) {
            throw new ValidationException('Category is required');
        }

        // Проверка статуса (если указан)
        if (isset($data['status']) && !Status::isValid($data['status'])) {
            throw new ValidationException('Invalid status. Must be: выполнена, не выполнена');
        }
    }
}
