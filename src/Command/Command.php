<?php

namespace App\Command;

use App\Exceptions\InvalidUserDataException;
use App\Exceptions\UserNotFoundException;
use App\Model\User;
use App\Response\ConsoleResponse;
use App\Service\UserService;
use Faker\Factory;

class Command
{
    public function __construct(
        private UserService $userService,
        private ConsoleResponse $consoleResponse
    ) {

    }

    private function helpMessage(): void
    {
        $this->consoleResponse->help();
    }
    public function execute(string $command, array $arguments = []): void
    {
        match ($command) {
            'list' => $this->showList(),
            'add' => $this->add($arguments),
            'delete' => $this->delete($arguments),
            default => $this->helpMessage()
        };
    }

    private function delete(array $arguments): void
    {
        if ($arguments === [] || !is_numeric($arguments[0])) {
            $this->consoleResponse->message("Укажите корректный id");
            return;
        }
        $id = (int) $arguments[0];
        try {
            $this->userService->delete($id);
            $this->consoleResponse->message("Пользователь удалён");
        } catch (UserNotFoundException $e) {
            $this->consoleResponse->message($e->getMessage());
        }
    }


    private function add(array $arguments): void
    {
        try {
            if ($arguments === []) {
                $faker = Factory::create('ru_RU');
                $user = new User(
                    null,
                    $faker->lastName,
                    $faker->firstName,
                    $faker->email
                );
            } else {
                if (count($arguments) !== 3) {
                    $this->consoleResponse->message("Неверное число аргументов");
                    return;
                }
                $user = new User(
                    null,
                    $arguments[0],
                    $arguments[1],
                    $arguments[2]
                );
            }
            $this->userService->add($user);
            $this->consoleResponse->message("Пользователь добавлен");
        } catch (InvalidUserDataException $e) {
            $this->consoleResponse->message($e->getMessage());
        }
    }
    private function showList(): void
    {
        $users = iterator_to_array($this->userService->getAll());
        if ($users === []) {
            $this->consoleResponse->message("Список пуст");
            return;
        }
        $this->consoleResponse->showUsers($users);
    }
}
