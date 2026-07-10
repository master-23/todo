<?php

/**
 * Сервис для работы с задачами
 *
 * Содержит бизнес-логику приложения для управления задачами.
 * Является посредником между контроллерами и репозиториями.
 *
 * @package App\Services
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Services;

use App\DTO\TaskDTO;
use App\Entities\Task;
use App\Enums\Priority;
use App\Enums\Status;
use App\Exceptions\TaskNotFoundException;
use App\Exceptions\ValidationException;
use App\Repositories\TaskRepository;
use App\Validators\TaskValidator;

class TaskService implements ServiceInterface
{
    /**
     * Репозиторий для доступа к данным
     *
     * @var TaskRepository
     */
    private TaskRepository $repository;

    /**
     * Валидатор данных задачи
     *
     * @var TaskValidator
     */
    private TaskValidator $validator;

    /**
     * Конструктор сервиса
     *
     * Инициализирует зависимости.
     */
    public function __construct()
    {
        $this->repository = new TaskRepository();
        $this->validator = new TaskValidator();
    }

    /**
     * Создает новую задачу
     *
     * @param TaskDTO $dto Данные для создания задачи
     * @return Task Созданная задача
     * @throws ValidationException Если данные не прошли валидацию
     *
     * @example
     * $dto = TaskDTO::fromArray($requestData);
     * $task = $service->createTask($dto);
     */
    public function createTask(TaskDTO $dto): Task
    {
        // Валидация данных
        $this->validator->validate([
            'title' => $dto->title,
            'description' => $dto->description,
            'due_date' => $dto->dueDate->format('Y-m-d H:i:s'),
            'priority' => $dto->priority->value,
            'category' => $dto->category,
            'status' => $dto->status->value
        ]);

        // Создание сущности
        $task = new Task(
            $dto->title,
            $dto->dueDate,
            $dto->priority,
            $dto->category,
            $dto->description,
            $dto->status,
            null,
            $dto->createdAt
        );

        // Сохранение в базу данных
        return $this->repository->save($task);
    }

    /**
     * Получает задачу по ID
     *
     * @param int $id ID задачи
     * @return Task Найденная задача
     * @throws TaskNotFoundException Если задача не найдена
     */
    public function getTask(int $id): Task
    {
        $task = $this->repository->findById($id);
        if ($task === null) {
            throw new TaskNotFoundException("Task with ID {$id} not found");
        }
        return $task;
    }

    /**
     * Получает список задач с фильтрацией
     *
     * @param string|null $search Поиск по названию (опционально)
     * @param string|null $sort Сортировка (due_date или created_at)
     * @return array<Task> Массив задач
     */
    public function getTasks(?string $search = null, ?string $sort = null): array
    {
        return $this->repository->findAll($search, $sort);
    }

    /**
     * Обновляет задачу
     *
     * Обновляет только те поля, которые переданы в запросе.
     * Остальные поля остаются без изменений.
     *
     * @param int $id ID задачи
     * @param array<string,string> $data Данные для обновления
     * @return Task Обновленная задача
     * @throws TaskNotFoundException Если задача не найдена
     * @throws ValidationException Если данные не прошли валидацию
     *
     * @example
     * $updated = $service->updateTask(1, [
     *     'title' => 'Новое название',
     *     'status' => 'выполнена'
     * ]);
     */
    public function updateTask(int $id, array $data): Task
    {
        // Получаем существующую задачу
        $task = $this->getTask($id);

        // Обновляем только переданные поля
        if (isset($data['title'])) {
            $task->setTitle($data['title']);
        }
        if (isset($data['description'])) {
            $task->setDescription($data['description']);
        }
        if (isset($data['due_date'])) {
            $task->setDueDate(new \DateTime($data['due_date']));
        }
        if (isset($data['priority']) && Priority::isValid($data['priority'])) {
            $task->setPriority(Priority::from($data['priority']));
        }
        if (isset($data['status']) && Status::isValid($data['status'])) {
            $task->setStatus(Status::from($data['status']));
        }
        if (isset($data['category'])) {
            $task->setCategory($data['category']);
        }

        // Валидация обновленных данных
        $this->validator->validate([
            'title' => $task->getTitle(),
            'description' => $task->getDescription(),
            'due_date' => $task->getDueDate()->format('Y-m-d H:i:s'),
            'priority' => $task->getPriority()->value,
            'category' => $task->getCategory(),
            'status' => $task->getStatus()->value
        ]);

        // Сохранение в базу данных
        return $this->repository->save($task);
    }

    /**
     * Удаляет задачу
     *
     * @param int $id ID задачи для удаления
     * @throws TaskNotFoundException Если задача не найдена
     */
    public function deleteTask(int $id): void
    {
        if (empty($id)) {
            throw new TaskNotFoundException("Task with ID {$id} not found");
        }

        $task = $this->getTask($id);
        if (!$task->getId()) {
            throw new TaskNotFoundException("Task with ID {$id} not found");
        }

        $this->repository->delete($id);
    }
}
