# Définitions

* 🔖 **Définitions**
* 🔖 **Aperçu**

___

## 📑 Définitions

Avant d'utiliser Docker, définitons certains termes essentiels.

### 🏷️ **Containers**

![image](https://raw.githubusercontent.com/seeren-training/Docker/master/wiki/resources/container.png)

Un conteneur est une unité logicielle standard qui regroupe le code et toutes ses dépendances afin que l'application s'exécute rapidement et de manière fiable d'un environnement informatique à un autre.

### 🏷️ **Image**

Une image de conteneur Docker est un package logiciel léger, autonome et exécutable qui comprend tout le nécessaire pour exécuter une application : code, environnement d'exécution, outils système, bibliothèques système et paramètres.

En raison de leur qualité en lecture seule, ces images sont parfois appelées instantanés. Ils représentent une application et son environnement virtuel à un moment précis. Cette cohérence est l'une des grandes fonctionnalités de Docker. Il permet aux développeurs de tester et d'expérimenter des logiciels dans des conditions stables et uniformes.

![image](https://raw.githubusercontent.com/seeren-training/Docker/master/wiki/resources/container-layers.png)

Étant donné que les images ne sont, en quelque sorte, que des modèles, vous ne pouvez pas les démarrer ou les exécuter. Ce que vous pouvez faire, c'est utiliser ce modèle comme base pour créer un conteneur. Un conteneur n'est, en fin de compte, qu'une image en cours d'exécution. Une fois que vous avez créé un conteneur, il ajoute un calque inscriptible au-dessus de l'image immuable, ce qui signifie que vous pouvez maintenant le modifier.

La base d'images sur laquelle vous créez un conteneur existe séparément et ne peut pas être modifiée. Lorsque vous exécutez un environnement conteneurisé, vous créez essentiellement une copie en lecture-écriture de ce système de fichiers (image docker) à l'intérieur du conteneur. Cela ajoute une couche de conteneur qui permet des modifications de la copie entière de l'image.

### 🏷️ **File**

Tout commence par un script d'instructions qui définit comment créer une image Docker spécifique. Ce script est appelé un Dockerfile. Le fichier exécute automatiquement les commandes décrites et crée une image Docker.

![image](https://raw.githubusercontent.com/seeren-training/Docker/master/wiki/resources/file.jpg)

> La commande pour créer une image à partir d'un Dockerfile est docker build.

L'image est ensuite utilisée comme modèle (ou base), qu'un développeur peut copier et l'utiliser pour exécuter une application. L'application a besoin d'un environnement isolé dans lequel s'exécuter – un conteneur.

> Pour créer une couche conteneur à partir d'une image, utilisez la commande docker create.
 
Enfin, après avoir lancé un conteneur à partir d'une image existante, vous démarrez son service et exécutez l'application.

___

## 📑 Aperçu

Je vous invite à consulter la vidéo suivante pour observer la manipulation des différentes définitions.

[How to Get Started with Docker](https://www.youtube.com/watch?v=iqqDU2crIEQ&t=324s)
![image](https://raw.githubusercontent.com/seeren-training/Docker/master/wiki/resources/overview.png)
