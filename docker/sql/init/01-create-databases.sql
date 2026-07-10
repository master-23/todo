SET NAMES utf8mb4;

-- Создаем основную базу данных
CREATE DATABASE IF NOT EXISTS task_manager
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Создаем тестовую базу данных
CREATE DATABASE IF NOT EXISTS task_manager_test
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- Предоставляем права пользователю
GRANT ALL PRIVILEGES ON task_manager.* TO 'task_user'@'%';
GRANT ALL PRIVILEGES ON task_manager_test.* TO 'task_user'@'%';
FLUSH PRIVILEGES;