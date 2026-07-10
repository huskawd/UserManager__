<?php

namespace App\Command;
use App\Model\User;
use App\Repository\UserRepositoryInterface;
class Command
{

    public function __construct(
        private UserRepositoryInterface $userRepository
    ){

    }


    public function execute(string $command, array $arguments = []):void{
        match ($command) {
            'list'=>$this->showList(),
            'add'=>$this->add($arguments),
            'delete'=>$this->delete($arguments),
            default=>$this->helpMessage()
        };
    }

    private function showList():void{
        $users = $this->userRepository->getAll();
        if (empty($users)){
            echo "Список пуст" . PHP_EOL;
            return;
        }
        foreach ($users as $user){
            echo $user->id . " | " .
            $user->surname . " " .
            $user->name. " | " .
            $user->email . PHP_EOL;
        }
    }
    private function helpMessage():void{
        echo "Неизвестная команда... Список доступных команд: list add delete";
    }

    private function add(array $arguments):void{
        $users = $this->userRepository->getAll();
        $max = 0;
        foreach ($users as $user){
             if ($user->id > $max){
                 $max = $user->id;
             }
        }
        $newId = $max +1;
        if (empty($arguments)){
            $faker = \Faker\Factory::create('ru_RU');
            $user = new User(
                $newId,
                $faker->lastName,
                $faker->firstName,
                $faker->email);
        }
        else{
            if(count($arguments) !== 3){
                echo "Неверное число аргументов".PHP_EOL;
                return;
            }
            $user = new User($newId, $arguments[0], $arguments[1], $arguments[2]);
        }
        $users[$newId]=$user;
        $this->userRepository->saveAll($users);
        echo "Пользователь добавлен" . PHP_EOL;
    }

    private function delete(array $arguments):void{
        if (empty($arguments) || !is_numeric($arguments[0])) {
            echo "Укажите корректный id" . PHP_EOL;
            return;
        }
        $id = (int) $arguments[0];
        $this->userRepository->delete($id);
    }

}