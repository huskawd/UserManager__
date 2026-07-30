<?php

namespace App\Repository;

use App\Config\ConfigBd;

class UserRepositoryFactory
{
    public function __construct(
        private readonly ConfigBd $config
    ) {
    }


    public function create(): UserRepositoryInterface
    {
        $bd = $this->config->getDataBase();
        if ($bd === 'json') {
            return new JsonUserRepository();
        }
        if ($bd === 'sql') {
            $pdoFactory = new PdoFactory($this->config);
            $pdo = $pdoFactory->create();
            return new SqlUserRepository($pdo);
        }
        return new JsonUserRepository();
    }

}
