<?php

namespace App\Repository;

use App\Model\User;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function delete(int $id): void;


    public function add(User $user): void;
}
