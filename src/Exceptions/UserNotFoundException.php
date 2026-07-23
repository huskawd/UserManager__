<?php

namespace App\Exceptions;

class UserNotFoundException extends \RuntimeException
{
    public function __construct(?string $message = "Пользователь с таким id не найден")
    {
        parent::__construct($this->message);
    }
}
