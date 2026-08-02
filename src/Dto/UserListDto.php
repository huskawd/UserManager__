<?php

namespace App\Dto;

class UserListDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $surname,
        public readonly string $name,
        public readonly string $email,
    ) {
    }
}
