<?php

namespace App\Repository;

use App\Exceptions\JsonFileProblemsException;
use App\Exceptions\UserNotFoundException;
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
        $ids = array_keys($users = $this->getAll());
        $id = 1;
        if ($users === []) {
            return $id;
        }
        sort($ids);
        return end($ids) + 1;
    }
    public function findById(int $id): ?User
    {
        $users = iterator_to_array($this->getAll());
        return $users[$id] ?? null;
    }

    public function delete(int $id): void
    {
        if ($this->findById($id) === null) {
            throw new UserNotFoundException();
        }
        $users = iterator_to_array($this->getAll());
        unset($users[$id]);
        $this->saveAll($users);
    }

    public function add(User $user): void
    {
        $users = iterator_to_array($this->getAll());
        $id = $this->getNewId();

        $newUser = new User(
            $id,
            $user->surname,
            $user->name,
            $user->email
        );

        $users[$id] = $newUser;
        $this->saveAll($users);
    }


    public function getAll(): iterable
    {
        $this->fixEmptyDirOrFile();
        $json = $this->readJsonFile();
        $data = $this->decodeJson($json);
        foreach ($data as $id => $userData) {
            $userData['id'] = (int) $id;
            $user = User::fromArray($userData);

            yield $user->id => $user;
        }
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
    {
        return json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    }

    private function writeJson(string $json): void // записываем JSON в файл
    {
        $finalContent = file_put_contents($this->jsonPath, $json);
        if ($finalContent === false) {
            throw new JsonFileProblemsException();
        }
    }

    private function readJsonFile(): string // читаем файл
    {
        $json = file_get_contents($this->jsonPath);
        if ($json === false) {
            throw new JsonFileProblemsException();
        }
        return $json;
    }

    private function decodeJson(string $json): array // JSON->Массив
    {
        $users = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
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
