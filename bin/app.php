<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Command\Command;
use App\Config\ConfigBd;
use App\Repository\UserRepositoryFactory;
use App\Service\UserService;
use App\Response\ConsoleResponse;

$config = new ConfigBd();
$logic = new UserRepositoryFactory($config);
$repository = $logic->create();
$userService = new UserService($repository);
$consoleResponse = new ConsoleResponse();
$command = new Command(
    $userService,
    $consoleResponse
);


if (isset($argv[1])) {
    $arguments = array_slice($argv, 2);
    $command->execute($argv[1],$arguments);
}
else{
    echo "Укажите команду Список доступных команд: list add delete";
}

