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
            throw new InvalidUserDataException("Некорректный id");
        }

        if (trim($surname) === '') {
            throw new InvalidUserDataException("Фамилия не может быть пустой");
        }

        if (trim($name) === '') {
            throw new InvalidUserDataException("Имя не может быть пустым");
        }

        if (trim($email) === '' || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidUserDataException("Некорректный адрес электронной почты");
        }

    }
    public static function fromArray(array $data): self
    {
        return  new User($data['id'], $data['surname'], $data['name'], $data['email']);
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
