<?php

namespace App\Config;

use App\Dto\DatabaseConfigDto;

class ConfigBd
{
    public function getDataBase(): string
    {
        return $_ENV['DB_SOURCE'] ?? 'json';
    }

    public function getDatabaseConfig(): DatabaseConfigDto
    {
        return new DatabaseConfigDto(
            host: $_ENV['DB_HOST'],
            port: (int)$_ENV['DB_PORT'],
            database: $_ENV['DB_NAME'],
            user: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
        );
    }



}
