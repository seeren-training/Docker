# Database

* 🔖 **Network**
* 🔖 **Exemple PHP**

___

## 📑 Network

Actuellement les images ne possèdent pas de connexion vers un système de base de données.

### 🏷️ **Création**

N'oubliez pas que les conteneurs, par défaut, s'exécutent de manière isolée et ne savent rien des autres processus ou conteneurs sur la même machine. Alors, comment permettons-nous à un conteneur de parler à un autre ? La réponse est le réseautage.

Créer un réseau pour la connection à la base de donénes.

```bash
docker network create hello-world-php-network
```

### 🏷️ **Container**

Le processus applicatif a besoin de communiquer à un autre processus par le biais du réseau.
Il existe deux manières de mettre un conteneur sur un réseau : l'affecter au démarrage ou connecter un conteneur existant. Pour l'instant, nous allons attacher le conteneur MySQL au démarrage.

Création d'un conteneur et attachement au réseau.

```bash
docker run -d \
    --network hello-world-php-network --network-alias mysql \
    --platform "linux/amd64" \
    -v hello-world-mysql-data:/var/lib/mysql \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_DATABASE=hello_world \
    mysql:5.7
```

Attentiin, vous n'avez pas besoin de spécifier la plateforme ou vous devez l'ajuster si vous n'êtes pas sur MAC OS M1.

Vous pouvez vérifier que la connexion se fasse en testant mysql.

```bash
docker exec -it <mysql-container-id> mysql -u root -p
```

### 🏷️ **Connection**

Vous pouvez alors lancer le conteneur applicatif en spécifiant le network et les variables d'environnement de connexion.

```bash
docker run -dp 8000:8000 \
--network hello-world-php-network \
-e MYSQL_HOST=mysql \
-e MYSQL_USER=root \
-e MYSQL_PASSWORD=root \
-e MYSQL_DB=hello_world \
hello-world-php
```

___

## 📑 Exemple PHP

Pour illustrer la connexion avec l'applicatif nous allons créer une méthode d'insertion et de lecture de donnée.

### 🏷️ **Prerequis**

Arretez le process

```bash
docker stop <process-name>
```
Supprimez le process

```bash
docker rm <process-name>
```
Supprimez l'image

```bash
docker rmi hello-world-php
```

Connectez vous à MySQL

```bash
docker exec -it <mysql-container-id> mysql -u root -p
```


Créez une table

```sql
CREATE TABLE IF NOT EXISTS message(
    id INT PRIMARY KEY AUTO_INCREMENT,
    word VARCHAR(32) NOT NULL
) ENGINE InnoDB;
```

### 🏷️ **Projet**

Nous allons créer un service pour la connexion, stocker nos informations dans un fichier de configuration et modifier le controller précédent.

*api/src/Service/ConnectionService.php*

Ce fichier possède une instance de PDO et reçoit en argument à la construction le dsn pour créer la connection.

```php
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

```

*api/config/services.json*

Ce fichier possède les informations de connections de notre exemple.

```json
{
    "parameters": {
        "dsn": "mysql:host=mysql;port=3306;dbname=hello_world;user=root;password=root;charset=utf8mb4"
    },
    "services": {
        "Api\\Service\\ConnectionService": {
            "dsn": ":dsn"
        }
    }
}
```

*api/src/Controller/HelloController.php*

Ce fichier possède une méthode de lecture et d'insertion d'un mot aléatoire.

```php
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
```

Nous allons alors construire l'image

```bash
docker build -t hello-world-php .
```

Puis la lancer

```bash
docker run -dp 8000:8000 \
--network hello-world-php-network \
-e MYSQL_HOST=mysql \
-e MYSQL_USER=root \
-e MYSQL_PASSWORD=root \
-e MYSQL_DB=hello_world \
hello-world-php
```

Maintenant quand nous arrêtons le processus et le relançons les données sont persistées.

L'adresse `http://localhost:8000/` liste les mots insérés.

L'adresse `http://localhost:8000/new` insède un mit aléatoire.