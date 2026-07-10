<?php

/**
 * Сущность задачи
 *
 * Представляет основную бизнес-сущность приложения - задачу.
 * Содержит все данные задачи и методы для работы с ними.
 *
 * @package App\Entities
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Entities;

use DateTime;
use App\Enums\Priority;
use App\Enums\Status;

class Task
{
    /**
     * Уникальный идентификатор задачи
     *
     * @var int|null null для новых задач, которые еще не сохранены в БД
     */
    private ?int $id;

    /**
     * Название задачи
     *
     * @var string Ограничение: максимум 255 символов
     */
    private string $title;

    /**
     * Подробное описание задачи
     *
     * @var string|null Может быть null, если описание не указано
     */
    private ?string $description;

    /**
     * Срок выполнения задачи
     *
     * @var DateTime Дата и время, до которого задача должна быть выполнена
     */
    private DateTime $dueDate;

    /**
     * Дата и время создания задачи
     *
     * @var DateTime Автоматически устанавливается при создании
     */
    private DateTime $createdAt;

    /**
     * Текущий статус задачи
     *
     * @var Status Статус из перечисления Status
     */
    private Status $status;

    /**
     * Приоритет задачи
     *
     * @var Priority Приоритет из перечисления Priority
     */
    private Priority $priority;

    /**
     * Категория задачи
     *
     * @var string Например: "Работа", "Дом", "Личное"
     */
    private string $category;

    /**
     * Конструктор задачи
     *
     * Создает новый экземпляр задачи со всеми необходимыми полями.
     * Если статус или дата создания не указаны, устанавливаются значения по умолчанию.
     *
     * @param string $title Название задачи
     * @param DateTime $dueDate Срок выполнения
     * @param Priority $priority Приоритет задачи
     * @param string $category Категория задачи
     * @param string|null $description Описание задачи (опционально)
     * @param Status|null $status Статус задачи (по умолчанию - не выполнена)
     * @param int|null $id ID задачи (для существующих задач)
     * @param DateTime|null $createdAt Дата создания (по умолчанию - текущее время)
     *
     * @example
     * $task = new Task(
     *     'Сделать отчет',
     *     new DateTime('2025-12-31 23:59:59'),
     *     Priority::HIGH,
     *     'Работа',
     *     'Подготовить квартальный отчет'
     * );
     */
    public function __construct(
        string $title,
        DateTime $dueDate,
        Priority $priority,
        string $category,
        ?string $description = null,
        ?Status $status = null,
        ?int $id = null,
        ?DateTime $createdAt = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
        $this->createdAt = $createdAt ?? new DateTime();
        $this->status = $status ?? Status::PENDING;
        $this->priority = $priority;
        $this->category = $category;
    }

    /**
     * Получает ID задачи
     *
     * @return int|null ID задачи или null для новой задачи
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Получает название задачи
     *
     * @return string Название задачи
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Получает описание задачи
     *
     * @return string|null Описание задачи или null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Получает срок выполнения
     *
     * @return DateTime Срок выполнения задачи
     */
    public function getDueDate(): DateTime
    {
        return $this->dueDate;
    }

    /**
     * Получает дату создания
     *
     * @return DateTime Дата и время создания задачи
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * Получает статус задачи
     *
     * @return Status Текущий статус
     */
    public function getStatus(): Status
    {
        return $this->status;
    }

    /**
     * Получает приоритет задачи
     *
     * @return Priority Приоритет задачи
     */
    public function getPriority(): Priority
    {
        return $this->priority;
    }

    /**
     * Получает категорию задачи
     *
     * @return string Категория задачи
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Устанавливает название задачи
     *
     * @param string $title Новое название
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Устанавливает описание задачи
     *
     * @param string|null $description Новое описание или null
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * Устанавливает срок выполнения
     *
     * @param DateTime $dueDate Новый срок выполнения
     * @return void
     */
    public function setDueDate(DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    /**
     * Устанавливает статус задачи
     *
     * @param Status $status Новый статус
     * @return void
     */
    public function setStatus(Status $status): void
    {
        $this->status = $status;
    }

    /**
     * Устанавливает приоритет задачи
     *
     * @param Priority $priority Новый приоритет
     * @return void
     */
    public function setPriority(Priority $priority): void
    {
        $this->priority = $priority;
    }

    /**
     * Устанавливает категорию задачи
     *
     * @param string $category Новая категория
     * @return void
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * Преобразует задачу в ассоциативный массив
     *
     * Используется для формирования JSON ответа API.
     * Все даты преобразуются в строки формата 'Y-m-d H:i:s'.
     *
     * @return array<string,mixed> Массив с данными задачи
     *
     * @example
     * $data = $task->toArray();
     * echo json_encode($data);
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->dueDate->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'category' => $this->category
        ];
    }
}
