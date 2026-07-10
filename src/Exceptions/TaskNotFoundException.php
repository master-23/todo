<?php

/**
 * Исключение для случая, когда задача не найдена
 *
 * Выбрасывается при попытке получить, обновить или удалить несуществующую задачу.
 *
 * @package App\Exceptions
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Exceptions;

use Exception;

class TaskNotFoundException extends Exception
{
}
