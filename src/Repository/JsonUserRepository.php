<?php

namespace App\Repository;
use App\Exceptions\JsonFileProblemsException;
use App\Model\User;


class JsonUserRepository implements UserRepositoryInterface
{
    private string $jsonPath;


    public function __construct(
        string $jsonPath = __DIR__ . "/../../data/users.json",
    ){
        $this->jsonPath = $jsonPath;
        $this->fixEmptyDirOrFile();
    }

    public function findById(int $id): ?User
    {
        $users = $this->getAll();
        foreach ($users as $user) {
            if ($id === $user->id) {
                return $user;
            }
        }
        return null;
    }

    public function delete(int $id):void{
        if ($this->findById($id) === null) {
            return;
        }
        $users = $this->getAll();
        unset($users[$id]);
        $this->saveAll($users);
    }

    public function getAll(): array
    {
        $this->fixEmptyDirOrFile();
        $json = $this->readJsonFile();
        $data = $this->decodeJson($json);
        $users = [];
        foreach ($data as $userData) {
            $user = User::fromArray($userData);
            $users[$user->id] = $user;
        }

        return $users;
    }

    public function saveAll(array $users): void
    {
        $this->fixEmptyDirOrFile();
        $data = [];
        foreach ($users as $user) {
            $data[] = $user->toArray();
        }
        $json = $this->encodeJson($data);
        $this->writeJson($json);
    }


    private function encodeJson(array $users):string{ //Массив->JSON
        $json = json_encode($users, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        if (json_last_error() !== JSON_ERROR_NONE){
            throw new JsonFileProblemsException();
        }
        return $json;
    }

    private function writeJson(string $json): void{ // записываем JSON в файл
        $finalContent = file_put_contents($this->jsonPath, $json);
        if ($finalContent === false){
            throw new JsonFileProblemsException();
        }
    }

    private function readJsonFile(): string{ // читаем файл
        $json = file_get_contents($this->jsonPath);
        if ($json === false ){
            throw new JsonFileProblemsException();
        }
        return $json;
    }

    private function decodeJson(string $json): array{ // JSON->Массив
        $users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($users)) {
            throw new JsonFileProblemsException();
        }
        $usersFiltered = [];
        foreach ($users as $userData) {
            $usersFiltered[$userData['id']] = $userData;
        }
        return $usersFiltered;
    }

    private function fixEmptyDirOrFile():void{
        $directory = dirname($this->jsonPath);

        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new JsonFileProblemsException();
            }
        }

        if (!is_file($this->jsonPath)) {
            file_put_contents($this->jsonPath, '[]');
        }
    }



}