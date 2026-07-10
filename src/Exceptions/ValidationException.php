<?php

/**
 * Исключение для ошибок валидации
 *
 * Выбрасывается, когда данные не проходят проверку валидатора.
 *
 * @package App\Exceptions
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception
{
}
