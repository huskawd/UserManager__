<?php

namespace App\Config;

class ConfigBd
{
    private array $config;
    public function __construct(){
        $this->config = parse_ini_file(__DIR__ . '/../../config/bd_choise.env');
    }
    public function getConfig():string{
        return $this->config['DB_SOURCE'] ?? 'json';
    }
}