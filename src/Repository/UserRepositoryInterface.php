<?php

namespace App\Repository;

use App\Model\User;

interface UserRepositoryInterface
{
    public function getAll(): iterable;

    public function delete(int $id): void;


    public function add(User $user): void;
}
