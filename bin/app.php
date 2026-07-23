<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Command\Command;
use App\Config\ConfigBd;
use App\Repository\UserRepositoryFactory;


$config = new ConfigBd();
$logic = new UserRepositoryFactory($config);
$repository = $logic->create();
$command = new Command($repository);


if (isset($argv[1])) {
    $arguments = array_slice($argv, 2);
    $command->execute($argv[1],$arguments);
}
else{
    echo "Укажите команду Список доступных команд: list add delete";
}

