<?php

/**
 * Репозиторий для работы с задачами в базе данных
 *
 * @package App\Repositories
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Repositories;

use PDO;
use DateTime;
use App\Entities\Task;
use App\Enums\Priority;
use App\Enums\Status;
use App\Config\Database;

class TaskRepository implements RepositoryInterface
{
    /**
     * Подключение к базе данных
     *
     * @var PDO
     */
    private PDO $db;

    /**
     * Конструктор репозитория
     *
     * Инициализирует подключение к базе данных.
     */
    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Сохраняет задачу в базу данных
     *
     * Если задача новая (без ID) - выполняет INSERT,
     * иначе - UPDATE существующей задачи.
     *
     * @param Task $task Задача для сохранения
     * @return Task Сохраненная задача с заполненным ID (для новой)
     *
     * @example
     * $task = new Task('Задача', ...);
     * $savedTask = $repository->save($task);
     */
    public function save(Task $task): Task
    {
        if ($task->getId() === null) {
            return $this->insert($task);
        }
        return $this->update($task);
    }

    /**
     * Создает новую запись в базе данных
     *
     * @param Task $task Задача для создания
     * @return Task Созданная задача с присвоенным ID
     * @throws \PDOException При ошибке выполнения запроса
     */
    private function insert(Task $task): Task
    {
        $sql = "INSERT INTO tasks (title, description, due_date, created_at, status, priority, category) 
                VALUES (:title, :description, :due_date, :created_at, :status, :priority, :category)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':due_date' => $task->getDueDate()->format('Y-m-d H:i:s'),
            ':created_at' => $task->getCreatedAt()->format('Y-m-d H:i:s'),
            ':status' => $task->getStatus()->value,
            ':priority' => $task->getPriority()->value,
            ':category' => $task->getCategory()
        ]);

        return new Task(
            $task->getTitle(),
            $task->getDueDate(),
            $task->getPriority(),
            $task->getCategory(),
            $task->getDescription(),
            $task->getStatus(),
            (int)$this->db->lastInsertId(),
            $task->getCreatedAt()
        );
    }

    /**
     * Обновляет существующую задачу
     *
     * @param Task $task Задача для обновления
     * @return Task Обновленная задача
     * @throws \PDOException При ошибке выполнения запроса
     */
    private function update(Task $task): Task
    {
        $sql = "UPDATE tasks SET 
                title = :title,
                description = :description,
                due_date = :due_date,
                status = :status,
                priority = :priority,
                category = :category
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':id' => $task->getId(),
            ':title' => $task->getTitle(),
            ':description' => $task->getDescription(),
            ':due_date' => $task->getDueDate()->format('Y-m-d H:i:s'),
            ':status' => $task->getStatus()->value,
            ':priority' => $task->getPriority()->value,
            ':category' => $task->getCategory()
        ]);

        return $task;
    }

    /**
     * Находит задачу по её ID
     *
     * @param int $id ID задачи
     * @return Task|null Объект задачи или null, если не найдена
     */
    public function findById(int $id): ?Task
    {
        $sql = "SELECT * FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);

        $data = $stmt->fetch();
        if (!$data) {
            return null;
        }

        return $this->hydrate($data);
    }

    /**
     * Находит все задачи с возможностью поиска и сортировки
     *
     * @param string|null $search Поиск по названию (LIKE)
     * @param string|null $sort Поле для сортировки (due_date или created_at)
     * @return array<Task> Массив объектов Task
     */
    public function findAll(?string $search = null, ?string $sort = null): array
    {
        $sql = "SELECT * FROM tasks";
        $params = [];

        // Добавляем условие поиска, если указан
        if ($search !== null) {
            $sql .= " WHERE title LIKE :search";
            $params[':search'] = '%' . $search . '%';
        }

        // Добавляем сортировку, если указана
        if ($sort === 'due_date') {
            $sql .= " ORDER BY due_date ASC";
        } elseif ($sort === 'created_at') {
            $sql .= " ORDER BY created_at ASC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $tasks = [];
        while ($data = $stmt->fetch()) {
            $tasks[] = $this->hydrate($data);
        }

        return $tasks;
    }

    /**
     * Удаляет задачу по ID
     *
     * @param int $id ID задачи для удаления
     * @return bool true если запись удалена или не существовала
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tasks WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Создает объект Task из данных базы данных
     *
     * Вспомогательный метод для преобразования данных из БД в объект.
     *
     * @param array<mixed> $data Ассоциативный массив с данными из БД
     * @return Task Объект задачи
     */
    private function hydrate(array $data): Task
    {
        return new Task(
            $data['title'],
            new DateTime($data['due_date']),
            Priority::from($data['priority']),
            $data['category'],
            $data['description'] ?? null,
            Status::from($data['status']),
            (int)$data['id'],
            new DateTime($data['created_at'])
        );
    }
}
