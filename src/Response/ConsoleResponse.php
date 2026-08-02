<?php

namespace App\Response;

class ConsoleResponse
{
    public function showUsers(iterable $users): void
    {
        foreach ($users as $user) {
            echo "{$user->id} {$user->surname} {$user->name} {$user->email}" . PHP_EOL;
        }
    }

    public function message(string $message): void
    {
        echo $message . PHP_EOL;
    }

    public function help(): void
    {
        echo "Неизвестная команда... Список доступных команд: list add delete" . PHP_EOL;
    }
}
