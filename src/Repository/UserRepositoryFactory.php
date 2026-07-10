<?php

namespace App\Repository;
use App\Config\ConfigBd;
class UserRepositoryFactory
{
    private ConfigBd $config;
    public function __construct(
        ConfigBd $config,
    ){
        $this->config = $config;
    }

    public function create(): UserRepositoryInterface
    {
        $bd = $this->config->getConfig();
        if ($bd === 'json') {
            return new JsonUserRepository();
        }
        if ($bd === 'sql') {
            return new SqlUserRepository();
        }
        return new JsonUserRepository();
    }

}