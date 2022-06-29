<?php

namespace Api\Controller;

use PDO;

use Seeren\Controller\JsonController;
use Seeren\Router\Route\Route;

use Api\Service\ConnectionService;

class HelloController extends JsonController
{

    #[Route("/", "GET")]
    public function show(ConnectionService $connectionService)
    {
        return $this->render([
            'messages' => $connectionService->get()->query('SELECT * FROM message')->fetchAll(PDO::FETCH_ASSOC)
        ]);
    }

    #[Route("/new", "GET")]
    public function new(ConnectionService $connectionService)
    {
        $words = ['Hello', 'Bonjour', 'Ciao', 'Hallo', 'Hola'];
        $connectionService->get()
            ->prepare('INSERT INTO message (word) VALUES (:word)')
            ->execute([
                'word' => $word = $words[array_rand($words)]
            ]);
        return $this->render([
            'id' => $connectionService->get()->lastInsertId(),
            'word' => $word
        ], 201);
    }

}
