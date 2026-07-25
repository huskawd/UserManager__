<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Config\ConfigBd;
use App\Repository\UserRepositoryFactory;
use App\Service\UserService;
use App\Model\User;

$config = new ConfigBd();
$factory = new UserRepositoryFactory($config);
$repository = $factory->create();
$userService = new UserService($repository);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($method === 'GET' && $uri === '/users') {
    $users = $userService->getAll();
    $result = [];
    foreach ($users as $user) {
        $result[] = [
            'id' => $user->id,
            'surname' => $user->surname,
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
    header('Content-Type: application/json');
    echo json_encode($result, JSON_UNESCAPED_UNICODE);

}

if ($method === 'POST' && $uri === '/users') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    $user = new User(
        null,
        $data['surname'],
        $data['name'],
        $data['email']
    );
    $userService->add($user);
}

if ($method === 'DELETE' && $uri === '/users') {
    $id = $_GET['id'];
    $userService->delete((int)$id);
}