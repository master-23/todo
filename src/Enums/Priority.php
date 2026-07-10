<?php

/**
 * Перечисление приоритетов задачи
 *
 * Определяет возможные уровни приоритета для задач.
 * Используется для классификации задач по важности.
 *
 * @package App\Enums
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Enums;

enum Priority: string
{
    /**
     * Низкий приоритет - задачи, которые можно выполнить позже
     */
    case LOW = 'низкий';

    /**
     * Средний приоритет - стандартные задачи
     */
    case MEDIUM = 'средний';

    /**
     * Высокий приоритет - срочные задачи
     */
    case HIGH = 'высокий';

    /**
     * Проверяет, является ли значение допустимым приоритетом
     *
     * @param string $value Значение для проверки
     * @return bool true если значение допустимо, false в противном случае
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'));
    }
}
