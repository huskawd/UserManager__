<?php

namespace App\Repository;

use App\Config\ConfigBd;

class UserRepositoryFactory
{

    public function __construct(
        private readonly ConfigBd $config
    ){}


    public function create(): UserRepositoryInterface
    {
        $bd = $this->config->getDataBase();
        if ($bd === 'json') {
            return new JsonUserRepository();
        }
        if ($bd === 'sql') {
            return new SqlUserRepository($this->config);
        }
        return new JsonUserRepository();
    }

}
