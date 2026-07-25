<?php

namespace App\Service;

use App\Model\User;
use App\Repository\UserRepositoryInterface;

class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {

    }
    public function getAll(): iterable
    {
        return $this->userRepository->getAll();
    }



    public function add(User $user): void
    {
        $this->userRepository->add($user);
    }

    public function delete(int $id): void
    {
        $this->userRepository->delete($id);
    }


}
