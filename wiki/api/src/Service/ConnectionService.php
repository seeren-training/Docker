<?php

namespace Api\Service;

use PDO;

class ConnectionService
{

    private PDO $pdo;

    public function __construct(string $dsn) {
        $this->pdo = new PDO($dsn, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public function get(): ?PDO
    {
        return $this->pdo ?? null;
    }
}
