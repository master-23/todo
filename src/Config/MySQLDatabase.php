<?php

/**
 * Адаптер для MySQL базы данных
 *
 * Реализует интерфейс DatabaseInterface для MySQL.
 * Использует паттерн Singleton для обеспечения единственного экземпляра соединения.
 *
 * @package App\Config
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Config;

use PDO;
use PDOException;

class MySQLDatabase implements DatabaseInterface
{
    /**
     * Единственный экземпляр соединения
     *
     * @var PDO|null
     */
    private ?PDO $connection = null;

    /**
     * Конфигурация подключения
     *
     * @var array<string,string>
     */
    private array $config;

    /**
     * Статус подключения
     *
     * @var bool
     */
    private bool $connected = false;

    /**
     * Конструктор адаптера MySQL
     *
     * @param array<string,string>|null $config Конфигурация подключения
     */
    public function __construct(?array $config = null)
    {
        $this->config = $config ?? [
            'host' => getenv('DB_HOST') ?: 'db',
            'dbname' => getenv('DB_NAME') ?: 'task_manager',
            'username' => getenv('DB_USER') ?: 'task_user',
            'password' => getenv('DB_PASSWORD') ?: 'task_password',
            'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
            'port' => getenv('DB_PORT') ?: '3306'
        ];
    }

    /**
     * Получает соединение с базой данных MySQL
     *
     * @return PDO Объект PDO для работы с базой данных
     * @throws PDOException Если не удалось подключиться
     */
    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            try {
                $dsn = sprintf(
                    "mysql:host=%s;port=%s;dbname=%s;charset=%s",
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['dbname'],
                    $this->config['charset']
                );

                $this->connection = new PDO(
                    $dsn,
                    $this->config['username'],
                    $this->config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']}"
                    ]
                );

                $this->connected = true;
            } catch (PDOException $e) {
                $this->connected = false;
                throw new PDOException("MySQL connection failed: " . $e->getMessage());
            }
        }

        return $this->connection;
    }

    /**
     * Проверяет подключение к базе данных
     *
     * @return bool true если подключение активно
     */
    public function isConnected(): bool
    {
        if (!$this->connected || $this->connection === null) {
            return false;
        }

        try {
            $this->connection->query('SELECT 1');
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Закрывает подключение к базе данных
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->connection = null;
        $this->connected = false;
    }

    /**
     * Получает название драйвера
     *
     * @return string Название драйвера
     */
    public function getDriver(): string
    {
        return 'mysql';
    }

    /**
     * Получает имя базы данных
     *
     * @return string Имя базы данных
     */
    public function getDatabaseName(): string
    {
        return $this->config['dbname'];
    }
}
