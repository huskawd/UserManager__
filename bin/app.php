<?php
declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

use App\Command\Command;
use App\TechRealisation\JsonLogic;

$jsonLogic = new JsonLogic();
$command = new Command($jsonLogic);


if (isset($argv[1])) {
    $arguments = array_slice($argv, 2);
    $command->execute($argv[1],$arguments);
}
else{
    echo "Укажите команду Список доступных команд: list add delete";
}
