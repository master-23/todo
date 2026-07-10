<?php

/**
 * Перечисление статусов задачи
 *
 * Определяет возможные состояния выполнения задачи.
 *
 * @package App\Enums
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Enums;

enum Status: string
{
    /**
     * Задача ожидает выполнения
     */
    case PENDING = 'не выполнена';

    /**
     * Задача выполнена
     */
    case COMPLETED = 'выполнена';

    /**
     * Проверяет, является ли значение допустимым статусом
     *
     * @param string $value Значение для проверки
     * @return bool true если значение допустимо, false в противном случае
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, array_column(self::cases(), 'value'));
    }
}
