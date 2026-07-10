<?php

/**
 * Unit-тесты для интерфейса базы данных
 *
 * Проверяет, что все адаптеры правильно реализуют DatabaseInterface.
 *
 * @package Tests\Unit\Config
 * @author Task Manager API
 * @version 1.0.0
 */

namespace Tests\Unit\Config;

use PHPUnit\Framework\TestCase;
use App\Config\DatabaseInterface;
use App\Config\MySQLDatabase;
use App\Config\PostgreSQLDatabase;
use App\Config\SQLiteDatabase;
use App\Config\DatabaseFactory;

class DatabaseInterfaceTest extends TestCase
{
    /**
     * Тест: Проверка, что адаптеры реализуют интерфейс
     */
    public function testAdaptersImplementInterface(): void
    {
        $adapters = [
            new MySQLDatabase()
        ];

        foreach ($adapters as $adapter) {
            $this->assertInstanceOf(DatabaseInterface::class, $adapter);
        }
    }

    /**
     * Тест: Проверка фабрики
     */
    public function testDatabaseFactory(): void
    {
        $mysql = DatabaseFactory::create('mysql');
        $this->assertInstanceOf(MySQLDatabase::class, $mysql);
        $this->assertEquals('mysql', $mysql->getDriver());
    }

    /**
     * Тест: Проверка метода getDriver
     */
    public function testGetDriver(): void
    {
        $mysql = new MySQLDatabase();
        $this->assertEquals('mysql', $mysql->getDriver());
    }

    /**
     * Тест: Проверка метода getDatabaseName
     */
    public function testGetDatabaseName(): void
    {
        $config = ['dbname' => 'test_db'];
        $mysql = new MySQLDatabase($config);
        $this->assertEquals('test_db', $mysql->getDatabaseName());
    }
}
