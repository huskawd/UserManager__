<?php

namespace App\Exceptions;

class JsonFileProblemsException extends \RuntimeException
{
    public function __construct(?string $message = "Проблемы при работе с файлом json")
    {
        parent::__construct($this->message);
    }
}
