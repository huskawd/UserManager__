<?php

namespace App\Command;
use App\TechRealisation\JsonLogic;
use App\Model\User;

class Command
{
    private JsonLogic $jsonLogic;
    public function __construct(JsonLogic $jsonLogic){
        $this->jsonLogic = $jsonLogic;
    }


    public function execute(string $command, array $arguments = []):void{
        if ($command === 'delete') {
            if (!isset($arguments[0]) || !is_numeric($arguments[0])) {
                echo "Укажите корректный id" . PHP_EOL;
                return;
            }
        }
        match ($command) {
            'list'=>$this->showList(),
            'add'=>$this->add($arguments),
            'delete'=>$this->delete($arguments[0]),
            default=>$this->helpMessage()
        };
    }

    private function showList():void{
        $users = $this->jsonLogic->getUsersFromJson();
        if (empty($users)){
            echo "Список пуст" . PHP_EOL;
            return;
        }
        foreach ($users as $user){
            echo $user->getId() . " | " .
            $user->getSurname() . " " .
            $user->getName(). " | " .
            $user->getEmail() . PHP_EOL;
        }
    }
    private function helpMessage():void{
        echo "Неизвестная команда... Список доступных комманд: list add delete";
    }

    private function add(array $arguments):void{
        $users = $this->jsonLogic->getUsersFromJson();
        $max = 0;
        foreach ($users as $user){
             if ($user->getId() > $max){
                 $max = $user->getId();
             }
        }
        $newId = $max +1;
        if (empty($arguments)){
            $user = new User($newId, "Фамилия".$newId, "Имя".$newId, "user".$newId."@gmail.com");
        }
        else{
            if(count($arguments) !== 3){ //или можно через рефлексию узнавать колво аргументов конструктора, но мне стало лень разбираться -_-
                echo "Неверное число аргументов";
                return;
            }
            $user = new User($newId, $arguments[0], $arguments[1], $arguments[2]); // здесь также можно обобщить.
        }
        $users[]=$user;
        $this->jsonLogic->saveUsersToJson($users);
        echo "Пользователь добавлен" . PHP_EOL;
    }

    private function delete(int $id):void{
        $users = $this->jsonLogic->getUsersFromJson();
        foreach ($users as $index => $user){
            if ($user->getId() === $id){
                unset($users[$index]);
                $this->jsonLogic->saveUsersToJson($users);
                echo "Пользователь удалён" . PHP_EOL;
                return;
            }
        }
        echo "Пользователь не с таким id не найден" . PHP_EOL;
    }

}