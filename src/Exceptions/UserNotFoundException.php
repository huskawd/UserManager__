<?php

namespace App\Exceptions;

class UserNotFoundException extends \RuntimeException
{
    protected $message = "Пользователь с таким id не найден";
    public function __construct(?string $message = null)
    {
        parent::__construct($this->message);
    }
}
