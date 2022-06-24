# Compose

* 🔖 **Pourquoi**
* 🔖 **Syntaxe**
* 🔖 **Exemple PHP**

___

## 📑 Pourquoi

Docker Compose est un outil qui a été développé pour aider à définir et partager des applications multi-conteneurs. Avec Compose, nous pouvons créer un fichier YAML pour définir les services et avec une seule commande, nous pouvons tout faire tourner ou tout détruire.

Le gros avantage de l'utilisation de Compose est que vous pouvez définir votre pile d'applications dans un fichier, la conserver à la racine de votre référentiel de projet et permettre facilement à quelqu'un d'autre de contribuer à votre projet. Quelqu'un aurait seulement besoin de cloner votre référentiel et de démarrer l'application de composition. En fait, vous pourriez voir pas mal de projets sur GitHub/GitLab faire exactement cela maintenant.

___

## 📑 Syntaxe

Le fichier doit en premier lieu spécifier la version

```yml
version: "3.7"
```

### 🏷️ **Services**

Nous allons pouvoir centraliser l'ensemble des conteneurs utilisant un network.

Précédement nous lancions un conteneur en spécifiant le mode détaché, les ports et le réseau avec les variables d'environnement.

```bash
docker run -dp 8000:8000 \
    -w /api -v "$(pwd):/api" \
    --network hello-world-php-network \
    -e MYSQL_HOST=mysql \
    -e MYSQL_USER=root \
    -e MYSQL_PASSWORD=root \
    -e MYSQL_DB=hello_world \
    hello-world-php
```

Nous pouvons stocker ces informations au format yml. Il suffit de le spécifier sous l'identifiant arbitraire de notre choix qui créera automatiquement un network.

```yml
  api:
    image: hello-world-php
    ports:
      - 8000:8000
    working_dir: /api
    volumes:
      - ./:/api
    environment:
      MYSQL_HOST: mysql
      MYSQL_USER: root
      MYSQL_PASSWORD: root
      MYSQL_DB: hello_world
```

Précédement nous avions lancé un conteneur à partir d'une iamge mysql en associant un network créé en amont ainsi qu'un volume et les variables d'environnement.

```bash
docker run -d \
    --network hello-world-php-network --network-alias mysql \
    --platform "linux/amd64" \
    -v hello-world-mysql-data:/var/lib/mysql \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_DATABASE=hello_world \
    mysql:5.7
```

Le mapping est identique et permet de composer plusieurs conteneurs.

```yml
  mysql:
    image: mysql:5.7
    volumes:
      - hello-world-php-mysql-data:/var/lib/mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: hello_world
```


### 🏷️ **Volume**

Lorsque nous avons exécuté le conteneur avec docker run, le volume nommé a été créé automatiquement. Cependant, cela ne se produit pas lors de l'exécution avec Compose. Nous devons définir le volume dans la section volumes de niveau supérieur : puis spécifier le point de montage dans la configuration du service
```yml
volumes:
  hello-world-php-mysql-data:
```

___

## 📑 Exemple PHP

Dans notre exemple, reprennons de zero.

Arrêter puis suprimez les conteneurs, les images, les volumes et les networks.

### 🏷️ **Images**

Il nous faut deux images, celle du projet et celle de mysql.

Concernant le projet, une image a été créée à partir de sources et du fichier Dockerfile.

```bash
docker build -t hello-world-php .
```

Concernant mysql elle a été téléchargée, attention la plateforme n'est necessaire qui si vous êtes sur Mac M1.

```bash
docker pull --platform "linux/amd64"  mysql:5.7  
```

### 🏷️ **Configuration**

La configuration est donc la suivante:

*api/docker-compose.yml*

```yml
version: "3.7"

services:
  api:
    image: hello-world-php
    ports:
      - 8000:8000
    working_dir: /api
    volumes:
      - ./:/api
    environment:
      MYSQL_HOST: mysql
      MYSQL_USER: root
      MYSQL_PASSWORD: root
      MYSQL_DB: hello_world
  mysql:
    image: mysql:5.7
    volumes:
      - hello-world-php-mysql-data:/var/lib/mysql
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: hello_world
volumes:
  hello-world-php-mysql-data:
```

### 🏷️ **Execution**

Pour démarrer les conteneurs.

```bash
docker-compose up -d
```

Pour arréter les conteneurs.

```bash
docker compose down
```

A cette étape vous pouvez démarrer vos services.

### 🏷️ **Utilisation**

Il nous manque un script de migration pour déployer notre table .

*api/migrations/tables.php*

```php
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
```

Relevons l'identifiant du conteneur.

```bash
docker ps
```
Créons la table necessaire.


```bash
docker exec <process id> php migrations/tables.php
```

Le projet persiste la donnée dans un fichier de services centralisé à l'adresse http://localhost:8000/.