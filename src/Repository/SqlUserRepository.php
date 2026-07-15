<?php

namespace App\Repository;

use App\Model\User;
use App\Config\ConfigBd;
use PDO;

class SqlUserRepository implements UserRepositoryInterface
{
    private PDO $pdo;
   public function __construct(
       ConfigBd $configBd
   )
   {
       $config  = $configBd->getDatabaseConfig();
       $dsn = sprintf(
           'mysql:host=%s;port=%s;dbname=%s',
           $config['host'],
           $config['port'],
           $config['database']);

           $this->pdo = new PDO(
               $dsn,
               $config['user'],
               $config['password']
           );
       $this->pdo->setAttribute(
           PDO::ATTR_ERRMODE,
           PDO::ERRMODE_EXCEPTION);
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
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $users = [];
        foreach ($data as $userData) {
            $user = User::fromArray($userData);
            $users[$user->id] = $user;
        }
        return $users;
    }

    public function add(User $user): void{
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
    }


}
