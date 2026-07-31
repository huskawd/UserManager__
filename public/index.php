<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Config\ConfigBd;
use App\Repository\UserRepositoryFactory;
use App\Service\UserService;
use App\Response\JsonResponse;
use App\Router\Router;

$config = new ConfigBd();
$factory = new UserRepositoryFactory($config);
$repository = $factory->create();
$userService = new UserService($repository);
$response = new JsonResponse();
$router = new Router($userService, $response);
$router->handle(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);