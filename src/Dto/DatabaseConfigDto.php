<?php

namespace App\Dto;

readonly class DatabaseConfigDto
{
    public function __construct(
        public string $host,
        public int $port,
        public string $database,
        public string $user,
        public string $password
    ) {
    }
}
