<?php

/**
 * Интерфейс валидатора
 *
 * Отвечает за проверку корректности данных перед сохранением.
 * Проверяет обязательные поля, форматы данных и допустимые значения.
 *
 * @package App\Validators
 * @author Task Manager API
 * @version 1.0.0
 */

namespace App\Validators;

use App\Exceptions\ValidationException;

interface ValidatorInterface
{
    /**
     * Проверяет валидность данных
     *
     * @param array<string,mixed> $data Данные для проверки
     * @throws ValidationException Если данные не прошли проверку
     * @return void
     */
    public function validate(array $data): void;
}
