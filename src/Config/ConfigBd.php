<?php

namespace App\Config;

use App\DtoDir\Dto;

class ConfigBd
{
    public function getDataBase(): string
    {
        return $_ENV['DB_SOURCE'] ?? 'json';
    }

    public function getDatabaseConfig(): Dto
    {
        return new Dto(
            host: $_ENV['DB_HOST'],
            port: (int)$_ENV['DB_PORT'],
            database: $_ENV['DB_NAME'],
            user: $_ENV['DB_USER'],
            password: $_ENV['DB_PASSWORD'],
        );
    }



}
