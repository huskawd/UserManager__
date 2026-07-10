<?php

namespace App\Repository;

use App\Model\User;

interface UserRepositoryInterface
{
    public function getAll(): array;

    public function saveAll(array $users):void;

    public function delete(int $id):void;

    public function findById(int $id): ?User;

}
