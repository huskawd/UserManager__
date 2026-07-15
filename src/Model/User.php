<?php

namespace App\Model;

use App\Exceptions\InvalidUserDataException;

readonly class User
{
    public function __construct(
        public ?int $id,
        public string $surname,
        public string $name,
        public string $email
    ) {
        if ($id !== null && $id <= 0) {
            throw new InvalidUserDataException("Id");
        }

        if (trim($surname) === '') {
            throw new InvalidUserDataException();
        }

        if (trim($name) === '') {
            throw new InvalidUserDataException();
        }

        if (trim($email) === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidUserDataException();
        }

    }
    public static function fromArray(array $data): self
    {
        return  new User($data['id'],$data['surname'], $data['name'], $data['email']);
    }
    public function toArray(): array
    {
        return [
            'surname' => $this->surname,
            'name' => $this->name,
            'email' => $this->email
        ];
    }


}
