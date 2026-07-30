<?php

namespace App\Exceptions;

class InvalidUserDataException extends \RuntimeException
{
    protected $message = "Некоректные данные";
    public function __construct(){
        parent::__construct($this->message);
    }
}