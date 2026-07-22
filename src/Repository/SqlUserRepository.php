<?php

namespace App\Repository;

use App\Exceptions\UserNotFoundException;
use App\Model\User;
use PDO;

class SqlUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {
        $this->createTableIfNotExists();
    }

    private function createTableIfNotExists(): void
    {
        $sql = <<<SQL
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                surname VARCHAR(255),
                name VARCHAR(255),
                email VARCHAR(255)
                );
 SQL;
        $this->pdo->exec($sql);
    }
    public function getAll(): iterable
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        while ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $user = User::fromArray($userData);
            yield $user->id => $user;
        }

    }

    public function add(User $user): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (surname, name, email) VALUES (:surname, :name, :email)");
        $stmt->execute([
            'surname' => $user->surname,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }


    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt -> execute(['id' => $id]);
        if ($stmt->rowCount() === 0) {
            throw new UserNotFoundException();
        }
    }


}
