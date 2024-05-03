# Informations

Il s'agit de mon projet d'examen, celui permettant de valider ma formation. Ce projet à été créé dans le but d'aider les
dans l'apprentissage. Il s'agit d'un projet pouvant servir dans un centre de formation comme l'école RI7, le centre où
j'ai réalisé ma formation.

# Attention

Le projet a été créé à l'aide d'un thème. Afin de respecter les droits du créateur du thème, les fichiers
du thème ont été supprimés. Afin de pouvoir initialiser le projet, vous devez obtenir le thème auprès de ce lien :
https://themeforest.net/item/ynex-bootstrap-admin-dashboard-template/45551445

# Prérequis


* NodeJS 18.12.1 et npm 8.19.2 https://nodejs.org/en/download/
* Composer 2.5.5 https://getcomposer.org/
* MySQL 8.0.32 https://dev.mysql.com/downloads/installer/
* Symfony CLI 5.5.1 https://scoop.sh/#/apps?q=symfony
* PHP 8.2.2 https://www.php.net/downloads.php
* Git 2.39.0 https://git-scm.com/download/win


# Installation

Le téléchargement de l’application se réalise à partir d’un repository sur github. réaliser le téléchargement dans une console de windows à l’aide de git.
```shell
git clone https://github.com/Manno-pascal/projet_Snorri.git
```
Dès que le projet est téléchargé, il est nécessaire d’activer les extensions PHP nécessaires.  Pour cela, il faut exécuter la commande suivante :
```shell
symfony check requirements
```
Cette commande permet de retourner l’ensemble des extensions nécessaires qui n’ont pas été activées :

```
Symfony Requirements Checker
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

> PHP is using the following php.ini file:
C:\php\php.ini

> Checking Symfony requirements:

....................W...........

                                              
 [OK]                                         
 Your system is ready to run Symfony projects 
                                              

Optional recommendations to improve your setup
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

 * intl extension should be available
   > Install and enable the intl extension (used for validators).


Note  The command console can use a different php.ini file
~~~~  than the one used by your web server.
      Please check that both the console and the web server
      are using the same PHP version and configuration.

```

L’activation des extensions se réalise donc en décommentant les lignes correspondantes aux extensions dans le fichier de config “php.ini” situé dans le dossier PHP.
Pour relier la base de données au projet, il faut configurer l’url de la base de données. Pour cela, il faut créer à la racine du projet un fichier nommé “.env.local” et y inscrire cette ligne en remplaçant les informations nécessaires par celles propres au serveur.
```shell
DATABASE_URL="mysql://**Nom d'utilisateur mysql**:**Mot de passe mysql**@127.0.0.1:3306/**Nom de la base**?serverVersion=8.0.32&charset=utf8mb4"
```
Modifiez les autres valeurs " *** change *** " dans le .env et .env.local.

Ensuite, il faut installer les paquets nécessaires à symfony et nodeJS.
```shell
composer install
npm install
```
Le projet est connecté à la base de données et comporte désormais l’ensemble des paquets nécessaires au bon fonctionnement. On exécutera les commandes suivantes afin de créer la base de données, créer les tables et vérifier que la base de données est conforme :
```shell
symfony console doctrine:database:create
symfony console doctrine:migration:migrate
symfony console doctrine:schema:validate
```
Dans le cas où la table est conforme, cela nous retournera ce message là :
```
Mapping
-------                                                                                                                     
 [OK] The mapping files are correct.   
                                                                                  
Database
--------                                                                                                                      
 [OK] The database schema is in sync with the mapping files.                                                                   
```
Le projet étant encore en développement, afin de faciliter le développement, nous pouvons charger des fixtures. Pour cela, il faut exécuter la commande suivante :
```shell
symfony console doctrine:fixtures:load
```
Nous utilisons fos-router afin d’utiliser, dans le javascript, le nom des routes au lieu de l’url. Nous devons donc exécuter la commande suivante afin de générer le json contenant l’association entre le nom des routes et l’url des routes :
```shell
symfony console fos:js-rounting:dump
```
Le projet est entièrement installé, il est désormais possible de lancer le serveur à l’aide des commandes suivantes :
```shell
npm run build
symfony serve -d
```
Le serveur est accessible en local grâce à l’url suivante : https://localhost:8000
