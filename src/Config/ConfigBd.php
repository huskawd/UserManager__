<?php

namespace App\Config;

class ConfigBd
{
    public function getDataBase(): string
    {
        return $_ENV['DB_SOURCE'] ?? 'json';
    }

    public function getDatabaseConfig():array{

        $config= [
            'host' => $_ENV['DB_HOST'],
            'port' => $_ENV['DB_PORT'],
            'database' => $_ENV['DB_NAME'],
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
        ];


        return $config;

    }



}
