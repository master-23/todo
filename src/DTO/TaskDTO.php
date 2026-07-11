<?php

/**
 * Data Transfer Object для задачи
 *
 * Используется для передачи данных между слоями приложения.
 * Содержит только данные, без бизнес-логики.
 * Обеспечивает типобезопасную передачу данных из запроса в сервис.
 *
 * @package App\DTO
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\DTO;

use DateTime;
use App\Enums\Priority;
use App\Enums\Status;
use App\Exceptions\ValidationException;
use App\Validators\TaskValidator;

class TaskDTO
{
    /**
     * Конструктор DTO
     *
     * @param string $title Название задачи
     * @param string|null $description Описание задачи
     * @param string $dueDate Срок выполнения
     * @param string $createdAt Дата создания
     * @param string $priority Приоритет
     * @param string $category Категория
     * @param string $status Статус
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly string $dueDate,
        public readonly string $createdAt,
        public readonly string $priority,
        public readonly string $category,
        public readonly string $status
    ) {}

    /**
     * Создает DTO из массива данных
     *
     * Преобразует данные из JSON запроса в типизированный объект DTO.
     * Выполняет преобразование строковых значений в соответствующие типы.
     *
     * @param array<string,string> $data Входные данные (из JSON запроса)
     * @return self Новый экземпляр TaskDTO
     * @throws ValidationException Если данные невалидны
     *
     * @example
     * $data = json_decode(file_get_contents('php://input'), true);
     * $dto = TaskDTO::fromArray($data);
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            dueDate: $data['due_date'],
            createdAt: $data['created_at'] ?? 'now',
            priority: $data['priority'],
            category: $data['category'],
            status: $data['status'] ?? 'не выполнена'
        );
    }
}
