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
      - [Changement du nom de la table `order` vers `purchase`](#changement-du-nom-de-la-table-order-vers-purchase)
      - [Changement de tous les prix de type `decimal` en `integer`.](#changement-de-tous-les-prix-de-type-decimal-en-integer)
    - [Table Slot - Conversion de la colonne days, `varchar` vers `json`](#table-slot---conversion-de-la-colonne-days-varchar-vers-json)
    - [Table Order/Purchase - Changement de la colonne `status` du type `varchar` vers `tinyint`](#table-orderpurchase---changement-de-la-colonne-status-du-type-varchar-vers-tinyint)
    - [Table User & Employee - Email unique](#table-user--employee---email-unique)
    - [Table OrderDetails - Colonne quantity, changement du type `decimal` vers `int`](#table-orderdetails---colonne-quantity-changement-du-type-decimal-vers-int)
    - [Conséquence de ces changements](#conséquence-de-ces-changements)
  - [IV. Compréhension du SI](#iv-compréhension-du-si)
    - [Règles de gestion](#règles-de-gestion)
      - [Application interne coté magasin](#application-interne-coté-magasin)
      - [Boutique en ligne coté client](#boutique-en-ligne-coté-client)
    - [Trigger](#trigger)
  - [V. optimisation de requêtes](#v-optimisation-de-requêtes)
    - [Description des produits](#description-des-produits)
    - [Purchase status](#purchase-status)
  - [VI. Symfony](#vi-symfony)
    - [Installation](#installation)
    - [Détails commande](#détails-commande)
    - [Mise à jour de la préparation d'purchase](#mise-à-jour-de-la-préparation-dpurchase)
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
  - On va changer le nom de cette table car `order` est un mot réservé en sql, on la remplacera par `purchase`.
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

#### Changement du nom de la table `order` vers `purchase`

```sql
ALTER TABLE `order`
RENAME TO `purchase`;
```

#### Changement de tous les prix de type `decimal` en `integer`.
  - On multiplie tous les résultats par 100 pour ne pas perdre de données.
  - On altère le type de la colonne

Ce qui nous donne pour les tables :

- order

```sql
// On étends la taille du nombre 
// pour ne pas avoir de problème 
// avec la multiplication par 100.
ALTER TABLE `purchase`
CHANGE `amount` `amount` decimal(8,2) NOT NULL,
CHANGE `toPay` `toPay` decimal(8,2) NOT NULL;

// On multiplie par 100
UPDATE `purchase`
SET `amount` = amount * 100, 
`toPay` = `toPay` * 100;

// On change le type
ALTER TABLE `purchase`
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

### Table Order/Purchase - Changement de la colonne `status` du type `varchar` vers `tinyint`

On suppose qu'il y a ces statuts pour une commande :
  - Created : 0
  - Prepared : 1
  - Canceled : 2

On peut donc choisir un tinyInt. Tous les tuples sont à `created` sauf l'id N°13 qui est à `prepared.` On peut donc faire :

```sql
ALTER TABLE `purchase`
ADD `status-int` tinyint NOT NULL AFTER `status`;

UPDATE `purchase` SET
`status-int` = '0'
WHERE `status` = "created";

UPDATE `purchase` SET
`status-int` = '1'
WHERE `status` = "prepared";

ALTER TABLE `purchase`
DROP `status`;

ALTER TABLE `purchase`
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

A la vue des données présente en base de données, et de la colonne `stock` de la table `product` qui est aussi un `integer`, on transforme la colonne `quantity` comme ceci :

```sql
ALTER TABLE `orderdetail`
CHANGE `quantity` `quantity` int NOT NULL;
```

### Conséquence de ces changements

Il faudra bien sûr répercuter tous ces changements sur les functions, procédures et trigger.

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

- Le client se connecte sur l'application, en vue d'effectuer un achat de véhicules miniaturisés. 
- Le client remplit son panier avec les produits qui l'intéresse.
- Ensuite :
  - Il peut enregistrer son panier et y revenir plus tard.
  - Ou valider sa commande et passer à l'achat.
- Lorsque le client à valider son achat, il paie. Un facture lui est délivré.
- Sa commande passe au statut `created`
- Le magasin la prépare
- Lorsque le magasin a fini de préparer la commande, le statut passe en `prepared`.
- A ce stade, le client peut à tout moment annuler sa commande qui passera en mode `canceled`
- Le client choisi un créneau disponible pour venir chercher sa commande.
- Le client se rend au magasin pour prendre sa commande.

### Trigger

- Expliquer en détail le rôle du déclencheur `insert_associated` sur la table Pack :

```sql
BEGIN
  DECLARE promo int;
  INSERT INTO `associatedproduct`(idProduct, idAssoproduct) 
  VALUES (NEW.idProduct, NEW.idPack);
  SET promo = getPackPromo(NEW.idPack);
  UPDATE product SET promotion = promo where id = NEW.idPack;
END
```
Le trigger est déclenché après une insertion dans la table `pack`.
On commence par `BEGIN` et on termine par `END`, ce qui indique une transaction SQL, si elle échoue on reviendra à l'état précédent.
...

## V. optimisation de requêtes
### Description des produits

- Mettre en place une recherche FullText sur la description des produits.
- Cette recherche sera a intégrer en tant que page exemple dans la partie framework ci-dessous.

On commence par ajouter un `FULLTEXT` sur la colonne `COMMENTS` :

```sql
ALTER TABLE `product`
ADD FULLTEXT `comments` (`comments`);
```

On effectue une recherche par exemple :
```sql
SELECT *
FROM product
WHERE MATCH(comments)
AGAINST ('Ford')
```

On remarque que le résultat nous renvoie une liste de tuples, avec en 1er le plus de résultat obtenu. Cela nous donne :

| id  | name | comments |
| --- | --- | --- |
| [52](?server=mysql&username=root&db=click-and-collect-improved&edit=product&where%5Bid%5D=52) | 1940s Ford truck | This 1940s `Ford` Pick-Up truck is re-created in 1:18 scale of original 1940s `Ford` truck. This antique style metal 1940s `Ford` Flatbed truck is all hand-assembled. This collectible 1940's Pick-Up truck is painted in classic dark green color, and features rotating wheels. |
| [17](?server=mysql&username=root&db=click-and-collect-improved&edit=product&where%5Bid%5D=17) | 1940 Ford Pickup Truck | This model features soft rubber tires, working steering, rubber mud guards, authentic `Ford` logos, detailed undercarriage, opening doors and hood, removable split rear gate, full size spare mounted in bed, detailed interior with opening glove box |
| [41](?server=mysql&username=root&db=click-and-collect-improved&edit=product&where%5Bid%5D=41) | 1985 Toyota Supra | This model features soft rubber tires, working steering, rubber mud guards, authentic `Ford` logos, detailed undercarriage, opening doors and hood, removable split rear gate, full size spare mounted in bed, detailed interior with opening glove box |
| [45](?server=mysql&username=root&db=click-and-collect-improved&edit=product&where%5Bid%5D=45) | 1976 Ford Gran Torino | Highly detailed 1976 `Ford` Gran Torino "Starsky and Hutch" diecast model. Very well constructed and painted in red and white patterns. |

### Purchase status
- Optimiser les requêtes portant sur le champ status de la table purchase, choisi comme critère de recherche, justifier et mesurer les performances de vos modifications.
- Vous prendrez comme exemple une requête quelconque utilisant le champ status en tant que critère.

## VI. Symfony

J'ai choisi de ne pas dockeriser l'application Symfony. J'ai donc `php 7.4` et `composer 2` installé en local.

### Installation

On vérifie si notre système est capable de faire tourner une application Symfony :

```sh
symfony check:requirements

Symfony Requirements Checker
~~~~~~~~~~~~~~~~~~~~~~~~~~~~

> PHP is using the following php.ini file:
/usr/local/etc/php/7.4/php.ini

> Checking Symfony requirements:

.............................


 [OK]
 Your system is ready to run Symfony projects


Note  The command console can use a different php.ini file
~~~~  than the one used by your web server.
      Please check that both the console and the web server
      are using the same PHP version and configuration.
```

- On crée le projet :

```sh
composer create-project symfony/skeleton symfony
```

- On se rend dans le dossier :

```sh
cd symfony
```

- Installation de l'ORM doctrine :

```sh
composer require symfony/orm-pack
```

- Installation de la CLI make

```sh
composer require --dev symfony/maker-bundle
```

- Il faut modifier la chaîne de connexion dans le fichier `.env` d'après la [doc](https://symfony.com/doc/current/doctrine.html#configuring-the-database):

```
DATABASE_URL="mysql://root:password@127.0.0.1:3306/click-and-collect-improved?serverVersion=8.0.29"
```

- On va tirer le modèle et construire les classes doctrine depuis la base de données en demande à Doctrine d'analyser la BDD :

```sh
php bin/console doctrine:mapping:import "App\Entity" annotation --path=src/Entity
```

- On ajoute les getters et setters :
```sh
php bin/console make:entity --regenerate App
```

- On ajoute une annotation en référence aux repositories permettant de manipuler nos entités, par exemple pour Basket : 

```php
/**
 * Basket
 *
 * ...
 * @ORM\Entity(repositoryClass="App\Repository\BasketRepository")
 */
class Basket
{
  ...
}
```

Une fois toute les entités modifiées, on lance la même commande pour créer les repositories :
```sh
php bin/console make:entity --regenerate App
```

On installe le moteur de vue pour afficher des pages HTML :

```sh
composer require symfony/twig-bundle
```

On va installer une librairie connue de rendu HTML/CSS mais pour ça on gère nos dépendances front avec NodeJS pour un meilleur contrôle.
```sh
composer require symfony/webpack-encore-bundle

```

### Détails commande
### Mise à jour de la préparation d'purchase
### Validation de panier

## VII. ORMs

## Ressources

- [Lien pour générer la table des matières](https://gist.github.com/JamieMason/c43e7ee1d078fc63e7c0f15746845c2e)
