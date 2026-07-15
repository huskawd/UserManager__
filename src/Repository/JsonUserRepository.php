<?php

namespace App\Repository;

use App\Exceptions\JsonFileProblemsException;
use App\Model\User;

class JsonUserRepository implements UserRepositoryInterface
{
    private string $jsonPath;


    public function __construct(
        string $jsonPath = __DIR__ . "/../../data/users.json",
    ) {
        $this->jsonPath = $jsonPath;
    }
    private function getNewId(): int
    {
        $users = $this->getAll();
        $max = 0;
        foreach ($users as $user) {
            if ($user->id > $max) {
                $max = $user->id;
            }
        }
        return $max + 1;
    }
    public function findById(int $id): ?User
    {
        $users = $this->getAll();
        return $users[$id] ?? null;
    }

    public function delete(int $id): void
    {
        if ($this->findById($id) === null) {
            echo "Пользователь с id {$id} не найден";
            return;
        }
        $users = $this->getAll();
        unset($users[$id]);
        $this->saveAll($users);
    }

    public function add(User $user): void{
        $users = $this->getAll();
        $id = $this->getNewId($users);

        $newUser = new User($id,
        $user->surname,
        $user->name,
        $user->email);

        $users[$id]=$newUser;
        $this->saveAll($users);
    }


    public function getAll(): array
    {
        $this->fixEmptyDirOrFile();
        $json = $this->readJsonFile();
        $data = $this->decodeJson($json);
        $users = [];
        foreach ($data as $id => $userData) {
            $userData['id'] = (int) $id;
            $user = User::fromArray($userData);
            $users[$user->id] = $user;
        }

        return $users;
    }

    private function saveAll(array $users): void
    {
        $this->fixEmptyDirOrFile();
        $data = [];
        foreach ($users as $user) {
            $data[$user->id] = $user->toArray();
        }
        $json = $this->encodeJson($data);
        $this->writeJson($json);
    }


    private function encodeJson(array $users): string //Массив->JSON
    {$json = json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new JsonFileProblemsException();
        }
        return $json;
    }

    private function writeJson(string $json): void // записываем JSON в файл
    {$finalContent = file_put_contents($this->jsonPath, $json);
        if ($finalContent === false) {
            throw new JsonFileProblemsException();
        }
    }

    private function readJsonFile(): string // читаем файл
    {$json = file_get_contents($this->jsonPath);
        if ($json === false) {
            throw new JsonFileProblemsException();
        }
        return $json;
    }

    private function decodeJson(string $json): array // JSON->Массив
    {$users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($users)) {
            throw new JsonFileProblemsException();
        }

        return $users;
    }

    private function fixEmptyDirOrFile(): void
    {
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
