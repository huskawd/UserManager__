<?php

namespace App\Model;

use App\Exceptions\InvalidUserDataException;

class User
{
    private int $id;
    private string $surname;
    private string $name;
    private string $email;

    public function __construct(int $id, string $surname, string $name, string $email){
        if ($id <= 0){
            throw new InvalidUserDataException();
        }
        $this->id = $id;
        if (trim($surname) === ''){
            throw new InvalidUserDataException();
        }
        $this->surname = $surname;
        if (trim($name)=== ''){
            throw new InvalidUserDataException();
        }
        $this->name = $name;
        if (trim($email) === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false){
            throw new InvalidUserDataException();
        }
        $this->email = $email;
    }

    public function getId(): int{
        return $this->id;
    }

    public function getSurname(): string{
        return $this->surname;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getEmail(): string{
        return $this->email;
    }

}