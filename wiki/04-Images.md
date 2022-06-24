# Images

* 🔖 **Build**
* 🔖 **Syntaxe**
* 🔖 **Exemple JS**
* 🔖 **Exemple PHP**

___

## 📑 Build

La commande build permet de créer une image à partir d'un fichier.

```bash
docker build .
```

Il est possible que le Dockerfile ne soit pas à la racine et l'on peut spécifier son emplacement.

```bash
docker build -f /path/to/a/Dockerfile .
```

Il est possible de spécifier une cible de repository et un tag.

```bash
docker build -t shykes/myapp:1.0.1 .
```

Une fois un conteneur lancé il est possible d'éxécuter des commandes pour ce dernier

```bash
docker exec <container-id> <command>
```

Nous allons maintenant observer la syntaxe du Dockerfile.

___

## 📑 Syntaxe

Le fichier docker soit s'appeler `Dockerfile` sans extensions.

### 🏷️ **FROM**

L'instruction FROM initialise une nouvelle étape de construction et définit l'image de base pour les instructions suivantes. En tant que tel, un Dockerfile valide doit commencer par une instruction FROM. L'image peut être n'importe quelle image valide. 

[FROM](https://docs.docker.com/engine/reference/builder/#from)

```bash
FROM [--platform=<platform>] <image> [AS <name>]
```

Les images se trouvent comme observé précédement sur le DockerHub. Par exemple si vous cherchez node sur DockerHub vous pouvez parcourir ses tags pour identifier la version désirée comme docker `node:16.15.1-alpine`.

```docker
FROM node:16.15.1-alpine
```

### 🏷️ **RUN**

RUN a 2 formes:

```bash
RUN <command>
```

```bash
RUN ["exécutable", "param1", "param2"]
```

L'instruction RUN exécutera toutes les commandes dans une nouvelle couche au-dessus de l'image actuelle et validera les résultats. L'image validée résultante sera utilisée pour l'étape suivante dans le Dockerfile.

Quelle commande exécuter? Si vous voulez installer des packages ou créer des dossiers ou encore mettre à jour votre package manager!

[RUN](https://docs.docker.com/engine/reference/builder/#run)

Par exemple pour installer python et C++ avec le package manager de la plateform linux (celle par défaut) et les builder:

```docker
RUN apk add --no-cache python2 g++ make
```

### 🏷️ **WORKDIR**

L'instruction WORKDIR définit le répertoire de travail pour toutes les instructions RUN, CMD, ENTRYPOINT, COPY et ADD qui le suivent dans le Dockerfile. Si le WORKDIR n'existe pas, il sera créé même s'il n'est utilisé dans aucune instruction Dockerfile ultérieure.

[WORKDIR](https://docs.docker.com/engine/reference/builder/#workdir)

```docker
WORKDIR /app
```

### 🏷️ **COPY**

COPY a deux formes:

```bash
COPY [--chown=<utilisateur>:<groupe>] <src>... <dest>
```

```bash
COPY [--chown=<utilisateur>:<groupe>] ["<src>",... "<dest>"]
```

Cette dernière forme est requise pour les chemins contenant des espaces.

[COPY](https://docs.docker.com/engine/reference/builder/#copy)

L'instruction COPY copie les nouveaux fichiers ou répertoires depuis src et les ajoute au système de fichiers du conteneur au chemin dest.

```docker
COPY . .
```

### 🏷️ **CMD**

CMD ne doit pas être confondu avec RUN.

Il ne peut y avoir qu'une seule instruction CMD dans un Dockerfile. Si vous répertoriez plus d'un CMD, seul le dernier CMD prendra effet.

L'objectif principal d'un CMD est de fournir des valeurs par défaut pour un conteneur en cours d'exécution. Ces valeurs par défaut peuvent inclure un exécutable ou omettre l'exécutable, auquel cas vous devez également spécifier une instruction ENTRYPOINT.

[CMD](https://docs.docker.com/engine/reference/builder/#cmd)

Dans notre cas nous allons faire un hello world node.js et allons demander à node d'éxécuter un index.

```docker
CMD ["node", "app.js"]
```

### 🏷️ **EXPOSE**

L'instruction EXPOSE informe Docker que le conteneur écoute sur les ports réseau spécifiés lors de l'exécution. Vous pouvez spécifier si le port écoute sur TCP ou UDP, et la valeur par défaut est TCP si le protocole n'est pas spécifié.

```bash
EXPOSE <port> [<port>/<protocol>...]
```

L'instruction EXPOSE ne publie pas réellement le port. Il fonctionne comme un type de documentation entre la personne qui construit l'image et la personne qui exécute le conteneur, sur les ports destinés à être publiés.

[EXPOSE](https://docs.docker.com/engine/reference/builder/#expose)

```docker
EXPOSE 3000
```

___

## 📑 Exemple JS

Nous allons créer un hello world en JavaScript pour illustrer le fonctionnement du Dockerfile.

### 🏷️ **Projet**

*app/package.json*

```json
{
  "name": "docker-exemple",
  "version": "1.0.0",
  "scripts": {
    "start": "node app.js"
  },
  "dependencies": {
    "express": "^4.18.1"
  }
}
```

*app/app.js*

```js

const express = require('express')
const app = express()
const port = 3000

app.get('/', (req, res) => {
  res.send('Hello World!')
})

app.listen(port, () => {
  console.log(`Example app listening on port ${port}`)
})
```

### 🏷️ **Dockerfile**

Le fichier demande l'installation de node, lance le script start et expose un post.

*app/Dockerfile*

```docker
FROM node:16.15.1-alpine
RUN apk add --no-cache python3 g++ make
WORKDIR /app
COPY . .
RUN npm install
CMD ["node", "app.js"]
EXPOSE 3000
```

Le projet demande l'installation de librairies, il faut alors les ignorer pour que ces fichiers ne soient pas inclu dans l'image.

*app/.dockerignore*

```dockerignore
.git
node_modules/
```

### 🏷️ **Build**

Comme observé à l'introduction il est possible de créer une image à partir de cette base.

```bash
docker build -t hello-world .
```

### 🏷️ **Exécution**

Comme observé précédemment vous pouvez utiliser cette image pour exécuter une instruction node sur une machine qui ne le possède pas en poussant cette image puis en la récupérant et en l'exécutant.

```bash
docker run -dp 3000:3000 hello-world
```
___

## 📑 Exemple PHP

Nous allons créer un hello world en JavaScript pour illustrer le fonctionnement du Dockerfile. Cet exemple sera un peu plus complet car il comprend l'installation de composer.

### 🏷️ **Projet**

*api/composer.json*
```json
{
    "name": "example/api",
    "autoload": {
        "psr-4": {
            "Api\\": "src/"
        }
    },
    "require": {
        "seeren/router": "^3.1"
    },
    "scripts": {
        "start" : "php -S 0.0.0.0:8000 -t public"
    }
}
```

*api/public/index.php*

```php
<?php

use Seeren\Router\Router;

include '../vendor/autoload.php';

(new Router())->getResponse();
```

*api/src/Controller/HelloController.php*

```php
<?php

namespace Api\Controller;

use Seeren\Controller\JsonController;
use Seeren\Router\Route\Route;

class HelloController extends JsonController
{

    #[Route("/", "GET")]
    public function show()
    {
        return $this->render(['message' => 'Hello World']);
    }

}
```

### 🏷️ **Dockerfile**

Le fichier demande l'installation des librairies d'archive zip, de composer puis lance le script install, start et expose un post.

*api/Dockerfile*

```docker
FROM php:8.1-fpm
RUN apt-get update \
    && apt-get install zip unzip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /api
COPY . .
RUN composer install \
    && composer dumpautoload
CMD ["composer", "start"]
EXPOSE 8000
```

*app/.dockerignore*

```dockerignore
.git
vendor/
```


### 🏷️ **Build**

Comme observé à l'introduction il est possible de créer une image à partir de cette base.

```bash
docker build -t hello-world-php .
```

### 🏷️ **Exécution**

Comme observé précédemment vous pouvez utiliser cette image pour exécuter une instruction node sur une machine qui ne le possède pas en poussant cette image puis en la récupérant et en l'exécutant.

```bash
docker run -dp 8080:8080 hello-world-php
```