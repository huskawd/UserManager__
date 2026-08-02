<?php

namespace App\Service;

use App\Dto\UserListDto;
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
        $users = $this->userRepository->getAll();

        $result = [];
        foreach ($users as $user) {
            $result[] = new UserListDto(
                $user->id,
                $user->surname,
                $user->name,
                $user->email
            );
        }
        return $result;
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
