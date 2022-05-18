# NFP-107 - Système de gestion de base de données - TP

[Ce document consiste à commenter la réalisation de ce TP.](https://slamwiki2.kobject.net/licence/nfp107/seance9)

## Table des matières

[Lien pour générer cette table](https://gist.github.com/JamieMason/c43e7ee1d078fc63e7c0f15746845c2e)

* [Requis](#requis)
* [I. Importation](#i-importation)
  * [Définition de l'infrastructure via docker-compose](#définition-de-linfrastructure-via-docker-compose)
  * [Adminer](#adminer)
* [II. Retroconception](#ii-retroconception)
  * [MySqlWorkbench](#mysqlworkbench)
  * [Déduction des règles métiers](#déduction-des-règles-métiers)

## Requis

`docker version >= 20.10.11`
`docker-compose version >= 1.29.2`

## I. Importation

### Définition de l'infrastructure via docker-compose

- Importation de la base de données depuis `sources/clickandcollect.sql` sur le conteneur Docker Mysql appelé `nfp-107-db`.
- Rdv sur le [docker hub](https://hub.docker.com/) afin de trouver une image `mysql`. Une des dernières versions stables est l'image [mysql:8](https://hub.docker.com/layers/mysql/library/mysql/8/images/sha256-6e866f4e8bf7e83d8c605fe0252e53219c23e4052cb22f7f23353d5bf800de63?context=explore)
- Création d'un fichier `docker-compose.yml` à la racine du projet avec le contenu suivant :

```yml
version: '3.7'
services:

  nfp-107-mysql:
    image: mysql:8
    container_name: nfp-107-mysql
    volumes:
      - ./sources/clickandcollect.sql:/docker-entrypoint-initdb.d/init.sql
      - ./.docker-volumes/mysql:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: clickandcollect
    networks:
      - nfp-107
    ports:
        3306:3306

  nfp-107-adminer:
    image: adminer:4
    restart: always
    ports:
      - 8080:8080
    networks:
      - nfp-107
    environment:
      ADMINER_DESIGN: pepa-linha
      ADMINER_DEFAULT_SERVER: nfp-107-mysql
    depends_on:
      - nfp-107-mysql
    
networks:
  nfp-107: {}
```

On définit 2 services, `mysql` et `adminer`. 

Les données MySQL seront persistées dans le dossier `.docker-volumes` grâce à la déclaration de volume : `./.docker-volumes/mysql:/var/lib/mysql`.

A la création de la base de données, si celle ci est vide, le fichier `clickandcollect.sql` sera chargé grâce au volume `./sources/clickandcollect.sql:/docker-entrypoint-initdb.d/init.sql`.

Ouvrir un terminal et se placer dans la racine du projet, lancer l'infrastructure avec la commande suivante : `docker-compose up -d`

### Adminer

Rendez-vous sur `http://localhost:8080/` et remplir les champs comme ceci:

![adminer-connection](./docs/adminer-connection.png)

Si la configuration est bonne, on devrait avoir ça :

![adminer-running](./docs/adminer-running.png)

## II. Retroconception  
A l'aide de MysqlWorkbench, un reverse ingeneering de la base de données va être effectué. On va passer du SQL au modèle conceptuel de données (MCD). 

### MySqlWorkbench

- Cliquer sur l'onglet `Database` > `Reverse Engineer` et choisir la base de donnée concernée.
- Cela devrait faire apparaître le schéma suivant :

![mwb-diagram](docs/mwb-diagram.png)

### Déduction des règles métiers

- Le système contient des utilisateurs qui peuvent avoir des commandes (order) et des paniers (basket) qui eux mêmes peuvent avoir plusieurs produits.
- Le système comporte des employés qui peuvent être responsable de plusieurs commandes.

