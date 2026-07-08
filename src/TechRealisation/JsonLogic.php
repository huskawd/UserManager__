<?php

namespace App\TechRealisation;
use App\Exceptions\JsonFileProblemsException;


class JsonLogic
{
    private string $jsonPath;
    private UserConverter $converter;

    public function __construct(){
        $this->jsonPath = __DIR__ . '/../../data/users.json';
        $this->converter = new UserConverter();
    }

    public function getUsersFromJson(): array
    {
        $this->checkJson();
        $json = $this->readJsonFile();
        $data = $this->decodeJson($json);
        $users = [];
        foreach ($data as $userData) {
            $users[] = $this->converter->convertToUser($userData);
        }

        return $users;
    }

    public function saveUsersToJson(array $users): void
    {
        $this->checkJson();
        $data = [];
        foreach ($users as $user) {
            $data[] = $this->converter->convertFromUser($user);
        }
        $json = $this->encodeJson($data);
        $this->writeJson($json);
    }


    private function encodeJson(array $users):string{ //Массив->JSON
        $json = json_encode($users, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new JsonFileProblemsException("Файл JSON пустой или повреждён.");
        }
        return $json;
    }

    private function writeJson(string $json): void{ // записываем JSON в файл
        $finalContent = file_put_contents($this->jsonPath, $json);
        if ($finalContent === false){
            throw new JsonFileProblemsException("Не удалось записать данные в JSON-файл.");
        }
    }

    private function readJsonFile(): string{ // читаем файл
        $json = file_get_contents($this->jsonPath);
        if ($json === false ){
            throw new JsonFileProblemsException("Не удалось прочитать JSON-файл");
        }
        return $json;
    }

    private function decodeJson(string $json): array{ // JSON->Массив
        $users = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new JsonFileProblemsException("Файл JSON пустой или повреждён.");
        }
        if (!is_array($users)) {
            throw new JsonFileProblemsException("JSON должен содержать массив пользователей.");
        }
        return $users;
    }

    private function checkJson(): void{// существует ли файл
        $directory = dirname($this->jsonPath);
        if (!is_dir($directory)){
            throw new JsonFileProblemsException("Папка с JSON не найдена.");
        }
        if (!is_file($this->jsonPath)){
            throw new JsonFileProblemsException("Файл JSON не найден.");
        }
    }




}