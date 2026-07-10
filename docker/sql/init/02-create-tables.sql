-- Переключаемся на основную базу
USE task_manager;
SET NAMES utf8mb4;

-- Создаем таблицу задач
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

-- Переключаемся на тестовую базу
USE task_manager_test;

-- Создаем такую же таблицу для тестов
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