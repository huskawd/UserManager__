<?php

namespace App\TechRealisation;
use App\Model\User;
class UserConverter
{
    public function convertToUser(array $data): User{
        return  new User($data['id'], $data['surname'], $data['name'], $data['email']);
    }
    public function convertFromUser(User $user): array{
        return [
            'id' => $user->getId(),
            'surname' => $user->getSurname(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ];
    }
}