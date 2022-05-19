# NFP-107 - Système de gestion de base de données - TP

[Ce document consiste à commenter la réalisation de ce TP.](https://slamwiki2.kobject.net/licence/nfp107/seance9)

## Table des matières
- [NFP-107 - Système de gestion de base de données - TP](#nfp-107---système-de-gestion-de-base-de-données---tp)
  - [Table des matières](#table-des-matières)
  - [Requis](#requis)
  - [I. Importation](#i-importation)
    - [Définition de l'infrastructure via docker-compose](#définition-de-linfrastructure-via-docker-compose)
    - [Adminer](#adminer)
  - [II. Retroconception](#ii-retroconception)
    - [*MySqlWorkbench*](#mysqlworkbench)
    - [*Querious*](#querious)
    - [Déduction des règles métiers](#déduction-des-règles-métiers)
  - [III. Optimisation du stockage](#iii-optimisation-du-stockage)
    - [Analyse](#analyse)
    - [Duplication de la table](#duplication-de-la-table)
    - [Requêtes SQL](#requêtes-sql)
      - [Changement de tous les prix de type `decimal` en `integer`.](#changement-de-tous-les-prix-de-type-decimal-en-integer)
    - [Table Slot - Conversion de la colonne days, `varchar` vers `json`](#table-slot---conversion-de-la-colonne-days-varchar-vers-json)
    - [Table Order - Changement de la colonne `status` du type `varchar` vers `int`](#table-order---changement-de-la-colonne-status-du-type-varchar-vers-int)
    - [Table User & Employee - Email unique](#table-user--employee---email-unique)
    - [Table OrderDetails - Colonne quantity, changement du type `decimal` vers `int`](#table-orderdetails---colonne-quantity-changement-du-type-decimal-vers-int)
  - [IV. Compréhension du SI](#iv-compréhension-du-si)
    - [Règles de gestion](#règles-de-gestion)
      - [Application interne coté magasin](#application-interne-coté-magasin)
      - [Boutique en ligne coté client](#boutique-en-ligne-coté-client)
    - [Trigger](#trigger)
  - [V. optimisation de requêtes](#v-optimisation-de-requêtes)
    - [Description des produits](#description-des-produits)
    - [Order status](#order-status)
  - [VI. Symfony](#vi-symfony)
    - [Détails commande](#détails-commande)
    - [Mise à jour de la préparation d'order](#mise-à-jour-de-la-préparation-dorder)
    - [Validation de panier](#validation-de-panier)
  - [VII. ORMs](#vii-orms)
  - [Ressources](#ressources)

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

  mysql:
    image: mysql:8
    container_name: mysql
    volumes:
      - ./sources/clickandcollect.sql:/docker-entrypoint-initdb.d/init.sql
      - ./.docker-volumes/mysql:/var/lib/mysql
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: password
      MYSQL_DATABASE: clickandcollect
    networks:
      - backend
    ports:
        3306:3306

  adminer:
    container_name: adminer
    image: adminer:4
    restart: always
    ports:
      - 8080:8080
    networks:
      - backend
    environment:
      ADMINER_DESIGN: pepa-linha
      ADMINER_DEFAULT_SERVER: mysql
    depends_on:
      - mysql
    
networks:
  nfp-107: {}
```

On définit 2 services, `mysql` et `adminer`. 

Les données MySQL seront persistées dans le dossier `.docker-volumes` grâce à la déclaration de volume : `./.docker-volumes/mysql:/var/lib/mysql`. On n'oublie surtout pas de déclarer ce dossier dans le fichier `.gitignore` pour ne pas envoyer ce dossier volumineux (~ 208 Mo) dans le dépôt Github.

A la création de la base de données, si celle ci est vide, le fichier `clickandcollect.sql` sera chargé grâce au volume `./sources/clickandcollect.sql:/docker-entrypoint-initdb.d/init.sql`.

Ouvrir un terminal et se placer dans la racine du projet, lancer l'infrastructure avec la commande suivante : `docker-compose up -d`

### Adminer

Rendez-vous sur `http://localhost:8080/` et remplir les champs comme ceci:

<img src="./docs/adminer-connection.png" style="display: block; margin: 1rem auto"/>

Si la configuration est bonne, on devrait avoir ça :

![adminer-running](./docs/adminer-running.png)

## II. Retroconception  
A l'aide de MysqlWorkbench, un reverse ingeneering de la base de données va être effectué. On va passer du SQL au modèle conceptuel de données (MCD). 

### *MySqlWorkbench*

- Cliquer sur l'onglet `Database` > `Reverse Engineer` et choisir la base de donnée concernée.
- Cela devrait faire apparaître le schéma suivant :

![mwb-diagram](docs/mwb-diagram.png)

### *Querious*

- Cliquer sur `View` puis `Structure` dans le menu.

![mwb-diagram](docs/querious-diagram.png)

### Déduction des règles métiers

- Le système contient des utilisateurs pouvant détenir des commandes (order) et des paniers (basket). Ces dernier peuvent contenir plusieurs produits (product ) qui eux même peuvent se retrouver dans plusieurs paniers. 
- Les commandes ont une durée de vie (timeslot) avec une date d'expiration.
- Le système comporte des employés pouvant être responsable de commandes.
- Lorsqu'un achat est effectué, une facture contenant la liste des produit pour une commande est établie (orderDetail)
- Un produit peut être associé à un autre produit (associatedproduct) et faire parti d'un paquet (pack). Les produits sont rangés par catégorie (section).

## III. Optimisation du stockage

### Analyse

Après vérification et analyse de la base de données, on peut effectuer les optimisations suivantes :

- Remarques générales
  - On peut changer tous les prix de type `decimal` en `integer` en prenant pour unité le centime. Exemple : 194,57 devient 19457. Cela simplifira les calculs coté applicatif et évitera de devoir effectuer des calculs d'arrondis supplémentaires. 
    - On dispose de prix ne dépassant pas les 1000€ avec 3 chiffres avant la virgule. Détails : On convertie `Decimals(6,2)` en entier pouvant atteindre `999999` maximum donc `MEDIUMINT` permettant une valeur maximum non signé de `16777215`. Le `SMALLINT` étant trop petit uniquement `65535`. [Source](https://dev.mysql.com/doc/refman/8.0/en/integer-types.html)
    - Le `MEDIUMINT` occupe 3 bytes d'espace tandis que le `DECIMAL(6,2)` occupe 4 bytes. [Source](https://dev.mysql.com/doc/refman/8.0/en/precision-math-decimal-characteristics.html)
  - ~~On peut convertir toutes les types `timestamp` en `datetime` pour faciliter les opérations de recherches et de tri.~~
- Table order
  - On peut changer le type du status en `integer` par une `enumération` coté applicatif car ils sont inscrits en dur dans un `VARCHAR(100)` ce qui limite l'évolution ou le changement et occupe de l'espace inutile.
- Table orderdetail
  - La colonne quantité ne possède que des `DECIMALS` ne contenant que des zéros après la virgule, on peut simplifier par des `INTEGER`.
- Table user et employee
  - On peut rendre la colonne email unique pour éviter les problèmes de duplication d'email.
  - ~~On pourrait fusionner la table user et employee pour simplifier le modèle.~~ 
- Table slot
  - On peut stocker les jours de la semaine dans un tableau au format json.

### Duplication de la table

Il va s'agir de changer les types énoncés plus haut. on donnera les requêtes par tables avec une description du changement :

- On duplique le fichier `clickandcollect.sql` pour le renommer en `click-and-collect-improved.sql`.
- Puis on recherche toutes les assertions `clickandcollect` pour les remplacer par `click-and-collect-improved`.
- Avec Adminer, on importe la base de données en sélectionnant `Importer` en haut à gauche.

<img src="./docs/adminer-import.png" style="display: block; margin: 1rem auto"/>

- On charge le fichier `click-and-collect-improved.sql` puis on éxecute. Tout devrait bien se temriner sans erreur.

<img src="./docs/adminer-select-file.png" style="display: block; margin: 1rem auto"/>

### Requêtes SQL

#### Changement de tous les prix de type `decimal` en `integer`.
  - On multiplie tous les résultats par 100 pour ne pas perdre de données.
  - On altère le type de la colonne

Ce qui nous donne pour les tables :

- order

```sql
// On étends la taille du nombre 
// pour ne pas avoir de problème 
// avec la multiplication par 100.
ALTER TABLE `order`
CHANGE `amount` `amount` decimal(8,2) NOT NULL,
CHANGE `toPay` `toPay` decimal(8,2) NOT NULL;

// On multiplie par 100
UPDATE `order`
SET `amount` = amount * 100, 
`toPay` = `toPay` * 100;

// On change le type
ALTER TABLE `order`
CHANGE `amount` `amount` MEDIUMINT NOT NULL,
CHANGE `toPay` `toPay` MEDIUMINT NOT NULL;
```

- product
```sql
ALTER TABLE `product`
CHANGE `price` `price` decimal(8,2) NOT NULL;
CHANGE `promotion` `promotion` decimal(8,2) NULL;

UPDATE `product`
SET `promotion` = 0, 
`price` = `price` * 100;

ALTER TABLE `product`
CHANGE `promotion` `promotion` MEDIUMINT NOT NULL,
CHANGE `price` `price` MEDIUMINT NOT NULL;
```

### Table Slot - Conversion de la colonne days, `varchar` vers `json`

Puisque toutes les valeurs de days sont à `"1,2,3,4,5"`, on peut tout remplacer sans condition.

```sql
ALTER TABLE `slot`
ADD `days-json` json NOT NULL;

UPDATE `slot` SET
`days-json` = '[0, 1, 2, 3, 4]';

ALTER TABLE `slot`
DROP `days`;

ALTER TABLE `slot`
CHANGE `days-json` `days` json NOT NULL AFTER `name`;
```

### Table Order - Changement de la colonne `status` du type `varchar` vers `int`

On suppose qu'il y a ces statuts pour une commande :
  - Created : 0
  - Prepared : 1
  - Canceled : 2

On peut donc choisir un tinyInt. Tous les tuples sont à `created` sauf l'id N°13 qui est à `prepared.` On peut donc faire :

```sql
ALTER TABLE `order`
ADD `status-int` tinyint NOT NULL AFTER `status`;

UPDATE `order` SET
`status-int` = '0'
WHERE `status` = "created";

UPDATE `order` SET
`status-int` = '1'
WHERE `status` = "prepared";

ALTER TABLE `order`
DROP `status`;

ALTER TABLE `order`
CHANGE `status-int` `status` tinyint NOT NULL;
```

### Table User & Employee - Email unique

```sql
ALTER TABLE `user`
ADD CONSTRAINT `Unique_Email` UNIQUE(email);

ALTER TABLE `employee`
ADD CONSTRAINT `Unique_Email` UNIQUE(email);
```

### Table OrderDetails - Colonne quantity, changement du type `decimal` vers `int`

A la vue des données présente en base de données, et de la colonne `stock` de la table `product` qui est aussi un `integer`, on peut transformer la colonne `quantity` nos données comme ceci :

```sql
ALTER TABLE `orderdetail`
CHANGE `quantity` `quantity` int NOT NULL;
```

## IV. Compréhension du SI

La base de données comprend plusieurs functions internes qui sont :

- getFreeEmployee :
  - Prend en entrée l'id de `timeslot`, et renvoi un id `d'employee`
  -  Permet de savoir si un employée à du temps libre par rapport à une fenêtre de temps.
- getPackPromo :
- isTimeslotExpired
- isTimeslotFull

### Règles de gestion
#### Application interne coté magasin


#### Boutique en ligne coté client

### Trigger

## V. optimisation de requêtes
### Description des produits
### Order status

## VI. Symfony
### Détails commande
### Mise à jour de la préparation d'order
### Validation de panier

## VII. ORMs

## Ressources

- [Lien pour générer la table des matières](https://gist.github.com/JamieMason/c43e7ee1d078fc63e7c0f15746845c2e)
