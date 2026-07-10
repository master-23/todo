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

class TaskDTO
{
    /**
     * Конструктор DTO
     *
     * @param string $title Название задачи
     * @param string|null $description Описание задачи
     * @param DateTime $dueDate Срок выполнения
     * @param DateTime $createdAt Дата создания
     * @param Priority $priority Приоритет
     * @param string $category Категория
     * @param Status $status Статус
     */
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly DateTime $dueDate,
        public readonly DateTime $createdAt,
        public readonly Priority $priority,
        public readonly string $category,
        public readonly Status $status
    ) {
    }

    /**
     * Создает DTO из массива данных
     *
     * Преобразует данные из JSON запроса в типизированный объект DTO.
     * Выполняет преобразование строковых значений в соответствующие типы.
     *
     * @param array<string,string> $data Входные данные (из JSON запроса)
     * @return self Новый экземпляр TaskDTO
     * @throws \InvalidArgumentException Если данные невалидны
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
            dueDate: new DateTime($data['due_date']),
            createdAt: new DateTime($data['created_at'] ?? 'now'),
            priority: Priority::from($data['priority']),
            category: $data['category'],
            status: Status::from($data['status'] ?? 'не выполнена')
        );
    }
}
