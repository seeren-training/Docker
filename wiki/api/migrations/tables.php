<?php

use Api\Service\ConnectionService;
use Seeren\Container\Container;

include __DIR__ . '/../vendor/autoload.php';

(new Container())
    ->get(ConnectionService::class)
    ->get()->exec("CREATE TABLE IF NOT EXISTS message(
        id INT PRIMARY KEY AUTO_INCREMENT,
        word VARCHAR(32) NOT NULL
    ) ENGINE InnoDB;");