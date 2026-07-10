<?php

/**
 * Фабрика для создания подключений к базе данных
 *
 * Создает экземпляры DatabaseInterface в зависимости от типа драйвера.
 *
 * @package App\Config
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Config;

use InvalidArgumentException;

class DatabaseFactory
{
    /**
     * Создает адаптер базы данных
     *
     * @param string $driver Тип драйвера (mysql, pgsql, sqlite)
     * @param array<string,string>|null $config Конфигурация подключения
     * @return DatabaseInterface Адаптер базы данных
     * @throws InvalidArgumentException Если драйвер не поддерживается
     */
    public static function create(string $driver, ?array $config = null): DatabaseInterface
    {
        switch (strtolower($driver)) {
            case 'mysql':
                return new MySQLDatabase($config);
            case 'pgsql':
            case 'postgresql':
                // return new PostgreSQLDatabase($config);
            case 'sqlite':
                // return new SQLiteDatabase($config);
            default:
                throw new InvalidArgumentException("Unsupported database driver: {$driver}");
        }
    }

    /**
     * Создает адаптер из переменных окружения
     *
     * @return DatabaseInterface Адаптер базы данных
     * @throws InvalidArgumentException Если драйвер не указан или не поддерживается
     */
    public static function createFromEnvironment(): DatabaseInterface
    {
        $driver = getenv('DB_DRIVER') ?: 'mysql';

        $config = [];

        switch (strtolower($driver)) {
            case 'mysql':
                $config = [
                    'host' => getenv('DB_HOST') ?: 'db',
                    'dbname' => getenv('DB_NAME') ?: 'task_manager',
                    'username' => getenv('DB_USER') ?: 'task_user',
                    'password' => getenv('DB_PASSWORD') ?: 'task_password',
                    'charset' => getenv('DB_CHARSET') ?: 'utf8mb4',
                    'port' => getenv('DB_PORT') ?: '3306'
                ];
                break;

            // case 'pgsql':
            // case 'postgresql':
            //     $config = [
            //         'host' => getenv('DB_HOST') ?: 'localhost',
            //         'dbname' => getenv('DB_NAME') ?: 'task_manager',
            //         'username' => getenv('DB_USER') ?: 'postgres',
            //         'password' => getenv('DB_PASSWORD') ?: '',
            //         'port' => getenv('DB_PORT') ?: 5432
            //     ];
            //     break;

            // case 'sqlite':
            //     $config = [
            //         'path' => getenv('DB_PATH') ?: __DIR__ . '/../../database/task_manager.db'
            //     ];
            //     break;

            default:
                throw new InvalidArgumentException("Unsupported database driver: {$driver}");
        }

        return self::create($driver, $config);
    }
}
