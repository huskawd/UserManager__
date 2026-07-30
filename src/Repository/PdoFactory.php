<?php

namespace App\Repository;

use App\Config\ConfigBd;
use PDO;

class PdoFactory
{
    public function __construct(
        private ConfigBd $configBd
    ) {
    }

    public function create(): PDO
    {
        $config  = $this->configBd->getDatabaseConfig();
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s',
            $config->host,
            $config->port,
            $config->database
        );
        $pdo = new PDO(
            $dsn,
            $config->user,
            $config->password
        );
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        return $pdo;
    }

}
