# Task Manager REST API

> RESTful API для управления задачами на PHP 8

## 📖 О проекте

**Task Manager REST API** — это тестовый функциональный бэкенд для системы управления задачами, построенный на чистом PHP 8. Проект представляет собой многослойное приложение с четким разделением ответственности.

### Ключевые особенности

- ✅ **Полноценное REST API** с поддержкой CRUD операций
- ✅ **Docker контейнеризация** для легкого развертывания
- ✅ **Автоматическое тестирование** (Unit + Integration)
- ✅ **Swagger документация** для всех API эндпоинтов
- ✅ **Гибкая система валидации** данных
- ✅ **Поддержка множества СУБД** (MySQL)
- ✅ **Типобезопасность** с использованием PHP 8

---

## 🎯 Функциональность

### Базовые операции

| Операция | Метод | URL | Описание |
|----------|-------|-----|----------|
| Создание | `POST` | `/api/tasks` | Создать новую задачу |
| Список | `GET` | `/api/tasks` | Получить список всех задач |
| Просмотр | `GET` | `/api/tasks/{id}` | Получить задачу по ID |
| Обновление | `PUT` | `/api/tasks/{id}` | Обновить задачу |
| Удаление | `DELETE` | `/api/tasks/{id}` | Удалить задачу |

### Расширенная функциональность

| Операция | Метод | URL | Описание |
|----------|-------|-----|----------|
| Фильтрация | `GET` | `/api/tasks?search=...` | Поиск по названию |
| Сортировка | `GET` | `/api/tasks?sort=due_date` | Сортировка по дате |

### Поля задачи

| Поле | Тип | Обязательно | Описание |
|------|-----|-------------|----------|
| `title` | string | ✅ | Название задачи (до 255 символов) |
| `description` | string | ❌ | Описание задачи |
| `due_date` | datetime | ✅ | Срок выполнения |
| `priority` | enum | ✅ | Приоритет: `низкий`, `средний`, `высокий` |
| `category` | string | ✅ | Категория задачи |
| `status` | enum | ❌ | Статус: `не выполнена`, `выполнена` |

---

## 🛠 Технологический стек

### Основные технологии

| Технология | Версия | Назначение |
|------------|--------|------------|
| **PHP** | 8.0+ | Основной язык программирования |
| **MySQL** | 8.0 | Система управления базами данных |
| **Docker** | 20.10+ | Контейнеризация |
| **Docker Compose** | 2.0+ | Оркестрация контейнеров |
| **Nginx** | Alpine | Веб-сервер |
| **PHP-FPM** | 8.0 | Обработчик PHP |

### Инструменты разработки

| Инструмент | Версия | Назначение |
|------------|--------|------------|
| **Composer** | latest | Менеджер зависимостей |
| **PHPUnit** | 9.5 | Фреймворк для тестирования |
| **PHPStan** | 1.0+ | Статический анализ кода |
| **PHP_CodeSniffer** | 3.7+ | Проверка стиля кода |

### Дополнительные инструменты

| Инструмент | Назначение |
|------------|------------|
| **phpMyAdmin** | Веб-интерфейс для управления БД |
| **Swagger/OpenAPI** | Документация API |
| **Make** | Автоматизация задач |

---

## 🏗 Архитектура

### Слои приложения

┌─────────────────────────────────────────────────────────────┐
│ Presentation Layer │
│ (HTTP Controllers) │
│ src/Controllers/ │
│ ControllerInterface │
└─────────────────────────┬───────────────────────────────────┘
│
┌─────────────────────────▼───────────────────────────────────┐
│ Business Layer │
│ (Services) │
│ src/Services/ │
│ TaskService.php │
└─────────────────────────┬───────────────────────────────────┘
│
┌─────────────────────────▼───────────────────────────────────┐
│ Data Layer │
│ (Repositories) │
│ src/Repositories/ │
│ TaskRepository │
│ RepositoryInterface │
└─────────────────────────┬───────────────────────────────────┘
│
┌─────────────────────────▼───────────────────────────────────┐
│ Database Layer │
│ (Database Adapters) │
│ src/Config/ │
│ DatabaseInterface │
│ ┌─────────────┬─────────────┬─────────────┐ │
│ │ MySQL │PostgreSQL │ SQLite │ │
│ └─────────────┴─────────────┴─────────────┘ │
└─────────────────────────────────────────────────────────────┘


### Компоненты архитектуры

| Компонент | Описание | Пример |
|-----------|----------|--------|
| **Controllers** | Обработка HTTP запросов, валидация, форматирование ответов | `TaskController` |
| **Services** | Бизнес-логика, координация между компонентами | `TaskService` |
| **Repositories** | Абстракция доступа к данным, CRUD операции | `TaskRepository` |
| **DTO** | Объекты для передачи данных между слоями | `TaskDTO` |
| **Entities** | Бизнес-сущности | `Task` |
| **Validators** | Проверка входных данных | `TaskValidator` |
| **Enums** | Перечисления (статусы, приоритеты) | `Priority`, `Status` |
| **Core** | Ядро приложения (маршрутизация, запросы) | `Router`, `Request` |

---

### Структура папок

task-manager-api/
├── docker/                          # Docker конфигурации
│   ├── mysql/
│   │   ├── conf/                   # Конфигурация MySQL
│   │   │   └── my.cnf
│   │   └── init/                   # Init скрипты для MySQL
│   │       ├── 01-create-databases.sql
│   │       └── 02-create-tables.sql
│   ├── nginx/
│   │   └── default.conf            # Конфигурация Nginx
│   └── scripts/                    # Скрипты управления Docker
│       ├── start.sh
│       ├── stop.sh
│       ├── test.sh
│       └── clean.sh
├── src/                             # Исходный код
│   ├── Config/                     # Конфигурация
│   │   ├── Database.php            # Основной класс БД (Facade)
│   │   ├── DatabaseFactory.php     # Фабрика адаптеров БД
│   │   ├── DatabaseInterface.php   # Интерфейс для БД
│   │   ├── MySQLDatabase.php       # Адаптер для MySQL
│   │   ├── PostgreSQLDatabase.php  # Адаптер для PostgreSQL
│   │   └── SQLiteDatabase.php      # Адаптер для SQLite
│   ├── Controllers/                # Контроллеры
│   │   ├── ControllerInterface.php # Интерфейс контроллера
│   │   └── TaskController.php      # Контроллер задач
│   ├── Core/                       # Ядро приложения
│   │   ├── Request.php             # Объект HTTP запроса
│   │   └── Router.php              # Маршрутизатор
│   ├── DTO/                        # Data Transfer Objects
│   │   └── TaskDTO.php             # DTO для задачи
│   ├── Entities/                   # Бизнес-сущности
│   │   └── Task.php                # Сущность задачи
│   ├── Enums/                      # Перечисления
│   │   ├── Priority.php            # Приоритеты
│   │   └── Status.php              # Статусы
│   ├── Exceptions/                 # Исключения
│   │   ├── TaskNotFoundException.php
│   │   └── ValidationException.php
│   ├── Repositories/               # Репозитории
│   │   ├── RepositoryInterface.php # Базовый интерфейс репозитория
│   │   ├── TaskRepositoryInterface.php
│   │   └── TaskRepository.php      # Репозиторий задач
│   ├── Services/                   # Сервисы
│   │   └── TaskService.php         # Сервис задач
│   └── Validators/                 # Валидаторы
│       ├── AbstractValidator.php   # Абстрактный валидатор
│       ├── TaskValidator.php       # Валидатор задач
│       └── ValidatorInterface.php  # Интерфейс валидатора
├── tests/                           # Тесты
│   ├── Unit/                       # Unit-тесты
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Core/
│   │   ├── DTO/
│   │   ├── Entities/
│   │   ├── Enums/
│   │   ├── Exceptions/
│   │   ├── Repositories/
│   │   └── Validators/
│   ├── Integration/                # Интеграционные тесты
│   │   ├── Repository/
│   │   └── Service/
├── public/                          # Публичная директория
│   │   ├── swagger/                 # Документация swagger
│   ├── index.php                   # Точка входа
│   └── .htaccess                   # Настройки Apache
├── scripts/                         # Скрипты
│   ├── migrate.php                 # Миграция БД
├── swagger/                         # Swagger документация
│   └── swagger.yaml                # OpenAPI спецификация
├── docs/                            # Документация
│   └── api/                        # Сгенерированная документация
├── logs/                            # Логи приложения
├── .env                             # Переменные окружения
├── .gitignore                       # Игнорируемые файлы Git
├── Dockerfile                       # Docker образ
├── docker-compose.yml              # Docker Compose конфигурация
├── Makefile                        # Автоматизация задач
├── composer.json                   # Зависимости PHP
├── phpunit.xml                     # Конфигурация PHPUnit
├── database.sql                    # Схема базы данных
└── README.md                       # Документация проекта

### Таблица tasks

CREATE TABLE IF NOT EXISTS tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    due_date DATETIME NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('не выполнена', 'выполнена') NOT NULL DEFAULT 'не выполнена',
    priority ENUM('низкий', 'средний', 'высокий') NOT NULL,
    category VARCHAR(100) NOT NULL,
    INDEX idx_status (status),
    INDEX idx_priority (priority),
    INDEX idx_due_date (due_date),
    INDEX idx_category (category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

 Для тестов создаётся тестовая база task_manager_test в том же контейнере. Для прода стоит отдельно переместить.
 И такая же таблица.

### Команды Make

make start          # Запуск всех контейнеров
make stop           # Остановка контейнеров
make restart        # Перезапуск контейнеров
make test           # Запуск тестов
make test-coverage  # Тесты с покрытием
make clean          # Полная очистка
make phpstan        # Статический анализ
make phpcs          # Проверка стиля кода
make phpcbf         # Исправление стиля кода

### Примеры запросов

# Создание задачи
curl -i -X POST -H 'Content-Type: application/json' -d '{ "title": "Задача1", "description": "Задача1 описание", "due_date": "2026-07-23T18:00:00", "priority": "низкий", "status": "не выполнена","category": "Работа" }' http://localhost:8080/api/tasks

# Получение списка задач
curl http://localhost:8080/api/tasks?search=тест&sort=due_date

# Получение задачи по ID
curl http://localhost:8080/api/tasks/1

# Обновление задачи
curl -i -X PUT -H 'Content-Type: application/json' -d '{ "title": "Задача2", "description": "Задача2 описание обновленное", "due_date": "2025-01-25T18:00:00", "priority": "низкий", "status": "выполнена" }' http://localhost:8080/api/tasks/2

# Удаление задачи
curl -i -X DELETE http://localhost:8080/api/tasks/3

## 🚀 Установка и запуск

### Требования

- Docker 20.10+
- Docker Compose 2.0+

### Быстрый старт

```bash
# 1. Клонирование репозитория
git clone <repository-url>
cd task-manager-api

# 2. Настройка переменных окружения для подключения к БД
cp .env.example .env
# Отредактируйте .env при необходимости

# 3. Запуск с Docker
make start

# 4. Проверка работы
curl http://localhost:8080/api/tasks

# 5. Запуск тестов
make test

Доступ к сервисам
Сервис	URL	Описание
API	http://localhost:8080	Основной API
phpMyAdmin	http://localhost:8081	Управление БД
Документация	swagger/swagger.yaml	OpenAPI спецификация
Swagger http://localhost:8080/swagger/ Подключена документация swagger

