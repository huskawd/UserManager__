<?php

namespace App\Exceptions;

use Throwable;

class JsonFileProblemsException extends \RuntimeException
{

        public function __construct($message){
            parent::__construct($message);
        }

}