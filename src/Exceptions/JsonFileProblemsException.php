<?php

namespace App\Exceptions;


class JsonFileProblemsException extends \RuntimeException
{
    protected $message = "Проблемы при работе с файлом json";
    public function __construct(){
        parent::__construct($this->message);
    }
}