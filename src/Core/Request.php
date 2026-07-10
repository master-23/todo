<?php

/**
 * Объект HTTP запроса
 *
 * Инкапсулирует все данные входящего HTTP запроса:
 * метод, путь, параметры, тело запроса.
 *
 * @package App\Core
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Core;

class Request
{
    /**
     * HTTP метод запроса
     *
     * @var string
     */
    private string $method;

    /**
     * Путь запроса (URI)
     *
     * @var string
     */
    private string $path;

    /**
     * Параметры запроса (GET параметры)
     *
     * @var array<string,string>
     */
    private array $params = [];

    /**
     * Тело запроса (для POST, PUT)
     *
     * @var array<string,string>
     */
    private array $body;

    /**
     * Конструктор запроса
     *
     * Инициализирует объект из суперглобальных переменных PHP.
     * Декодирует JSON тело запроса.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = $_SERVER['REQUEST_URI'];
        $this->body = json_decode((string)file_get_contents('php://input'), true) ?? [];
        $this->params = array_merge($this->params, $_GET);
    }

    /**
     * Получает HTTP метод
     *
     * @return string HTTP метод (GET, POST, PUT, DELETE)
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Получает путь запроса
     *
     * @return string URI путь
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Получает все параметры запроса
     *
     * @return array<string,string> Массив параметров
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Получает тело запроса
     *
     * @return array<string,string> Декодированный JSON из тела запроса
     */
    public function getBody(): array
    {
        return $this->body;
    }

    /**
     * Получает параметр по ключу
     *
     * @param string $key Имя параметра
     * @param mixed $default Значение по умолчанию
     * @return mixed Значение параметра или значение по умолчанию
     */
    public function getParam(string $key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    /**
     * Устанавливает параметр запроса
     *
     * @param string $key Имя параметра
     * @param mixed $value Значение параметра
     * @return void
     */
    public function setParam(string $key, $value): void
    {
        $this->params[$key] = $value;
    }
}
