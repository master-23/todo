<?php

/**
 * Конфигурация базы данных (Facade)
 *
 * Предоставляет простой интерфейс для работы с базой данных.
 * Использует фабрику для создания адаптеров и реализует паттерн Singleton.
 *
 * @package App\Config
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    /**
     * Единственный экземпляр адаптера базы данных
     *
     * @var DatabaseInterface|null
     */
    private static ?DatabaseInterface $instance = null;

    /**
     * Приватный конструктор для предотвращения создания экземпляров извне
     */
    private function __construct()
    {
    }

    /**
     * Получает соединение с базой данных
     *
     * @return PDO Объект PDO для работы с базой данных
     * @throws PDOException Если не удалось подключиться
     */
    public static function getConnection(): PDO
    {
        return self::getInstance()->getConnection();
    }

    /**
     * Получает адаптер базы данных
     *
     * @return DatabaseInterface Адаптер базы данных
     */
    public static function getInstance(): DatabaseInterface
    {
        if (self::$instance === null) {
            self::$instance = DatabaseFactory::createFromEnvironment();
        }

        return self::$instance;
    }

    /**
     * Устанавливает адаптер базы данных
     *
     * @param DatabaseInterface $adapter Адаптер базы данных
     * @return void
     */
    public static function setInstance(DatabaseInterface $adapter): void
    {
        self::$instance = $adapter;
    }

    /**
     * Проверяет подключение к базе данных
     *
     * @return bool true если подключение активно
     */
    public static function isConnected(): bool
    {
        return self::getInstance()->isConnected();
    }

    /**
     * Закрывает подключение к базе данных
     *
     * @return void
     */
    public static function disconnect(): void
    {
        self::getInstance()->disconnect();
    }

    /**
     * Получает название драйвера
     *
     * @return string Название драйвера
     */
    public static function getDriver(): string
    {
        return self::getInstance()->getDriver();
    }

    /**
     * Получает имя базы данных
     *
     * @return string Имя базы данных
     */
    public static function getDatabaseName(): string
    {
        return self::getInstance()->getDatabaseName();
    }
}
