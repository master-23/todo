<?php

/**
 * Интерфейс для подключения к базе данных
 *
 * Определяет контракт для всех адаптеров базы данных.
 *
 * @package App\Config
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Config;

use PDO;

interface DatabaseInterface
{
    /**
     * Получает соединение с базой данных
     *
     * @return PDO Объект PDO для работы с базой данных
     * @throws \PDOException Если не удалось подключиться
     */
    public function getConnection(): PDO;

    /**
     * Проверяет подключение к базе данных
     *
     * @return bool true если подключение активно
     */
    public function isConnected(): bool;

    /**
     * Закрывает подключение к базе данных
     *
     * @return void
     */
    public function disconnect(): void;

    /**
     * Получает название драйвера базы данных
     *
     * @return string Название драйвера (mysql, pgsql, sqlite и т.д.)
     */
    public function getDriver(): string;

    /**
     * Получает имя базы данных
     *
     * @return string Имя базы данных
     */
    public function getDatabaseName(): string;
}
