<?php

namespace App\DtoDir;

readonly class Dto
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
