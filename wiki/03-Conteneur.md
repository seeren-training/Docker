# Les conteneurs

* 🔖 **Lancement**
* 🔖 **Les conteneurs**
* 🔖 **Les images**

___

## 📑 Lancement

**Lancer un conteneur à partir d'une image** locale ou distante. 

```bash
docker run docker/getting-started
```

Si l'image "*docker/getting-started*" n'est pas trouvée en locale elle sera téléchargée du **Docker Registry**.

[docker/getting-started](https://hub.docker.com/r/docker/getting-started)

En utilisant `run` un processus est lancé. Ce processus est isolé de votre système et ne pouvez y accéder. Pour l'arréter vous pouver utiliser ctrl + c.
___

## 📑 Les conteneurs

Un processus est l'exécution d'un conteneur à partir d'une image.

**Lister les processus actifs** et cachés.

```bash
docker ps -a
```

L'on remarque que chaque processus a un nom qui est affecté par default par Docker ainsi qu'un status indiquant s'il est démarré ou arrété. 

**Demarrer un processus** ([process_name] doit être remplacé par le nom du processus relevé).

```bash
docker start [process_name]
```

**Arréter un processus**.

```bash
docker stop [process_name]
```

**Supprimer un processus**.

```bash
docker rm [process_name]
```

**Lancer un processus nommé** par vous même vous pouvez utiliser l'option ``--name``.

```bash
docker run --name your-name docker/getting-started
```

**Lancer un processus détaché** vous pouvez utiliser l'option `-d`. Le processus s'éxécute alors en tache d'arrière plan. Cela correspond à arréter le processus et à le démarré unitairement avec la commande observée précédement.

```bash
docker run -d docker/getting-started
```

Nous avons signalé que les processus étaient isolés. Pour intégir avec eux il faudra le mapper vers un port de votre machine vers un port du container.

**Mapper un processus** en utilisant l'option `-p`.

```bash
docker run -p 8080:80 docker/getting-started
```

L'option **-dp unifie** processus détaché et mapping de port.

```bash
docker run -dp 8080:80 docker/getting-started
```

Vous pouvez alors accéder à l'application contenue dans l'image en naviguant jusqu'au port 8080 de votre machine.

**Afficher les logs d'un processus**.

```bash
docker logs [process_name]
```

**Suivre les logs d'un processus**.

```bash
docker logs [process_name] -f
```

___

## 📑 Les images

**Lister les images**.

```bash
docker images
```
**Supprimer une image** ([image_name] doit être remplacé par le nom d'une image listée)..

```bash
docker rmi [image_name]
```

**Télécharger une image**.

```bash
docker pull [image_name]
```
**Créer une image à partir d'une autre** ([user_name] doit être remplacé par votre nom d'utilisateur docker)

```bash
docker tag [image_name] [user_name]/[new_image_name]
```

### 🏷️ **Docker Hub**

Les images sont récupérées comme observé précédement sur [Docker Hub](https://hub.docker.com/search?type=image).

Vous pouvez poussez une image créer localement sur le hub en commenceant par créer un compte puis un repository à l'image de ce qui ce fait sur github. Pour pousser du contenu il faut également être connectéé au service.

**Se connecter** à Docker Hub

```bash
docker login
```

**Pousser une image**

```bash
docker push [user_name]/[new_image_name]
```
