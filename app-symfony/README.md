---
marp: true
theme: uncover
---

# Symfony

## Table des matières

  * [Installation](#installation)
  * [Détails commande](#détails-commande)
  * [Mise à jour de la préparation d'une purchase](#mise-à-jour-de-la-préparation-dune-purchase)
  * [Validation de panier](#validation-de-panier)
  * [Implémentation](#implémentation)
    * [Recherche fulltext](#recherche-fulltext)
  * [Optimisation](#optimisation)
    * [Mesure de performance](#mesure-de-performance)
    * [Le problème du N+1](#le-problème-du-n1)
      * [Etat du problème](#etat-du-problème)
      * [1ère Solution - Appeler les entités associées dans le contrôlleur](#1ère-solution---appeler-les-entités-associées-dans-le-contrôlleur)
      * [2ème solution - Spécifier à Doctrine le chargement automatique](#2ème-solution---spécifier-à-doctrine-le-chargement-automatique)
      * [3ème solution - Utiliser le cache de Twig](#3ème-solution---utiliser-le-cache-de-twig)
    * [Le problème des associations](#le-problème-des-associations)
    * [Exemple pour l'affichage des produits](#exemple-pour-laffichage-des-produits)

J'ai choisi de ne pas dockeriser l'application Symfony. J'ai donc `php 7.4` et `composer 2` installé en local.

## Installation

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

On se rend compte que certaines entités devront être créées à la main car Doctrine ne détecte pas les colonnes sur les tables pivots en dehors des clées étrangères.

  - On créé l'entité en faisant référence à la bonne table avec les annotations
  - Et on renseigne dans les entitées Purchase et Product, une collection de OrderDetails en OneToMany.

On installe le moteur de vue pour afficher des pages HTML :

```sh
composer require symfony/twig-bundle
```

On va installer une librairie connue de rendu HTML/CSS mais pour ça on gère nos dépendances front avec NodeJS pour un meilleur contrôle.
```sh
composer require symfony/webpack-encore-bundle
npm i
npm bootstrap bootstrap-icons @popperjs/core
## Pour lancer le serveur de dev avec hot-reload
npm run dev-server
```

## Détails commande
## Mise à jour de la préparation d'une purchase
## Validation de panier

## Implémentation

### Recherche fulltext

La recherche fulltext n'étant pas implémentée dans Doctrine, il va falloir écrire une requête sql native et hydrater le résultat avec l'entité Product associé à Section.

[Doc Doctrine à propos des requêtes natives](https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/reference/native-sql.html#resultsetmappingbuilder)

Dans la classe `ProductRepository` :

```php
{...}
/**
 * @return Product[] Returns an array of Product objects
 */
public function fulltextSearch(string $searchTerm)
{
    $rsm = new ResultSetMapping();
    $rsm->addEntityResult(Product::class, 'p')
        ->addFieldResult('p', 'id', 'id')
        ->addFieldResult('p', 'name', 'name')
        ->addFieldResult('p', 'comments', 'comments')
        ->addFieldResult('p', 'promotion', 'promotion')
        ->addFieldResult('p', 'image', 'image')
        ->addFieldResult('p', 'price', 'price')
        ->addFieldResult('p', 'updated_at', 'updatedAt')
        ->addFieldResult('p', 'stock', 'stock')
        ->addJoinedEntityResult(Section::class, 's', 'p', 'section')
        ->addFieldResult('s', 'section_id', 'id')
        ->addFieldResult('s', 'section_name', 'name');

    $sql = "SELECT p.id, p.name, p.comments, p.promotion, p.image, p.price, p.updated_at, p.stock, s.id as section_id, s.name as section_name " . 
            "FROM product as p " . 
            "JOIN section as s ON s.id = p.idSection ".
            "WHERE MATCH (comments) AGAINST ( ? );";

    $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);
    $query->setParameter(1, $searchTerm);

    return $query->getResult();
}
```

Dans la vue on crée un formulaire :

```html
<form>
    <input type="text" value="{{ app.request.query.get('search') }}" name="search">
    <button type="submit">Search</button>
</form>
```

Dans le controlleur `ProductController`, on appelle la méthode comme ceci :

```php
{...}
/**
 * @Route("/products", name="products_index")
 */
public function index(ProductRepository $productRepo, Request $request): Response
{
    if (empty($request->get('search'))) {
        $products = $productRepo->findBy([], ['section' => 'ASC']);
    } else {
        $products = $productRepo->fulltextSearch($request->get('search'));
    }

    return $this->render('product/index.html.twig', [
        'products' => $products,
        // 'sections' => $sectionRepo->findAll()
    ]);
}
{...}
```

Si le paramètre `search` dans l'url n'est pas vide, on effectue la recherche fulltext.

## Optimisation

### Mesure de performance

Nous pouvons mesurer les performances de notre application avec le profiler, il s'installe avec la commande suivante :

```bash
composer require --dev symfony/profiler-pack
```

Ensuite, on se rend sur l'addresse `https://127.0.0.1:8000/_profiler` pour acceder à l'analyse complète de notre application, on va particulièrement s'intéresser à la partie Doctrine.

### Le problème du N+1

#### Etat du problème

Si on essaie d'afficher la page `/products`, et que l'on affiche les requêtes effectuées avec doctrine, on obtient ces résultats :

- 10 Database Queries
- 2 Different statements
- 21.57 ms Query time
- 3 Invalid entities

En dessous, nous avons les requêtes effectuées :

```sql
# La requête qui récupère nos entités Products :
SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.comments AS comments_3,
  t0.stock AS stock_4,
  t0.image AS image_5,
  t0.price AS price_6,
  t0.promotion AS promotion_7,
  t0.idSection AS idSection_8
FROM
  product t0
ORDER BY
  t0.idSection ASC

# Cette requête est éxecutée 9 fois !
# Avec à chaque fois des paramètres différents.
SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.description AS description_3
FROM
  section t0
WHERE
  t0.id = ?
```

Le problème vient de la vue `product/index.html.twig` qui appelle l'entité associée `Section` d'un `Product` et comme cet appel se fait dans une boucle, on enchaine les requêtes à chaque fois. Doctrine est quand assez intelligent pour ne pas rappeler une entité stockée en mémoire. Ce problème pourrait grossir davantage si la liste des `sections` venait à grossir.

Voici le souci dans la vue:

```html
{% for key, p in products %}
  {...}
  <small class="text-muted">{{ p.section.name }}</small>
  {...}
{% endfor %}
```

#### 1ère Solution - Appeler les entités associées dans le contrôlleur

Une solution serait d'effectuer toutes les requêtes d'un seul coup dans le controlleur puis de les placer dans une collection de `Section` est d'appeler ce tableau dans la vue.

On modifie le fichier `ProductController` :

```php
class ProductController extends AbstractController
{
  /**
   * @Route("/products", name="products_index")
   */
  public function index(ProductRepository $productRepo, SectionRepository $sectionRepo): Response
  {
      return $this->render('product/index.html.twig', [
          'products' => $productRepo->findBy([], ['section' => 'ASC']),
+         'sections' => $sectionRepo->findAll()
      ]);
  }
}
```

Même sans utiliser cette nouvelle variable dans la vue, les entités ne sont plus appelées depuis la vue mais sont chargées depuis le contrôlleur et doctrine les garde en mémoire, ce qui nous donne comme résultats :

- 2 Database Queries
- 2 Different statements
- 5.50 ms Query time
- 3 Invalid entities

avec comme requêtes :

```sql
SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.comments AS comments_3,
  t0.stock AS stock_4,
  t0.image AS image_5,
  t0.price AS price_6,
  t0.promotion AS promotion_7,
  t0.idSection AS idSection_8
FROM
  product t0
ORDER BY
  t0.idSection ASC

SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.description AS description_3
FROM
  section t0
```

On a donc réduit le temps de la requête d'environ trois quart. De manière, dans toute l'application on évitera d'effectuer des appels à doctrine dans des boucles.

#### 2ème solution - Spécifier à Doctrine le chargement automatique

On peut grâce à une annotation sur l'association préciser que l'on veut qu'elle soit chargée dés que l'on appellera une entité. Dans notre cas on veut que les sections soient toujours appelées lorsque l'on affiche les produits.

Cela s'active avec l'annotation `fetch="EAGER"`.

Implémentation dans l'entité `Product` :

```php
class Product
{
    {...}

    /**
     * @var Section
     *
     * @ORM\ManyToOne(targetEntity="Section", fetch="EAGER")
     * {...}
     */
    private $section;
    
    {...}
}
```

On effectue un test sur la page `/products`. On passe bien de 2 à 1 requête effectuée avec un `left join`.

```sql
SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.comments AS comments_3,
  t0.stock AS stock_4,
  t0.image AS image_5,
  t0.price AS price_6,
  t0.promotion AS promotion_7,
  t0.idSection AS idSection_8,
  t9.id AS id_10,
  t9.name AS name_11,
  t9.description AS description_12
FROM
  product t0
  LEFT JOIN section t9 ON t0.idSection = t9.id
ORDER BY
  t0.idSection ASC
```

L'inconvénients de cette méthode est que l'appel des sections de cette méthode se fera à chaque fois que les produits seront appelés. Sinon, il faudra écrire une requête DQL au cas par cas.

#### 3ème solution - Utiliser le cache de Twig

Une autre solution serait de mettre en cache nos cartes de produits avec twig.

On installe l'extension twig pour gérer les caches :

```bash
composer require twig/cache-extra
composer require twig/extra-bundle
```

On isole les cartes de la page `product` dans un fichier twig nommé `_card.html.twig`. Mais pour savoir le moment où le cache sera invalide, on va devoir surveiller la mise à jour des éléments, on va donc créer une propriété `updatedAt` sur les produits.

On ajoute une propriété sur `Product` :

```bash
php bin/console make:entity product

 Your entity already exists! So let s add some new fields!

 New property name (press <return> to stop adding fields):
 > updatedAt

 Field type (enter ? to see all types) [datetime_immutable]:
 >

 Can this field be null in the database (nullable) (yes/no) [no]:
 >

 updated: src/Entity/Product.php
 >

  Success!


 Next: When you re ready, create a migration with php bin/console make:migration
```

Création de la migration avec :

```bash
php bin/console make:migration
```

Application avec :

```bash
php bin/console d:m:m
```

On implémente l'utilisation du cache avec Twig. On vérifie que le datetime updatedAt n'a pas été modifié, dans le contraire on utilise le cache.

```html
{% cache 'product;' ~ p.id ~ ";" ~ p.updatedAt %}
<a href="{{ path('product_detail', { id: p.id }) }}" class="text-decoration-none link-secondary">
	<div class="col">
		<div class="card shadow-sm">
			<img src="https://picsum.photos/200/100?random={{ key }}" alt="">
			<div class="card-body">
				<h4>{{ p.name }}</h4>
				<p class="card-text">{{ (p.comments | slice(0, 50)) ~ '...' }}</p>
				<p class="card-text">{{ p.price | amount }}</p>
				<div class="d-flex justify-content-between align-items-center">
					<div class="btn-group">
						<button type="button" class="btn btn-sm btn-outline-secondary">Details</button>
						<form action="{{ path('cart_increment_qty', { id: p.id }) }}">
							<button type="submit" class="btn btn-sm btn-outline-secondary">Add to cart
								<i class="bi bi-cart-fill"></i>
							</button>
						</form>
					</div>
					<small class="text-muted">{{ p.section.name }}</small>
				</div>
			</div>
		</div>
	</div>
</a>
{% endcache %}
```

Désormais lorsque la page `product` est appelée, les entités `Sections` ne sont plus appelées :

- 1 Database Queries
- 1 Different statements
- 3.36 ms Query time
- 3 Invalid entities

Voici la requête :

```sql
SELECT
  t0.id AS id_1,
  t0.name AS name_2,
  t0.comments AS comments_3,
  t0.stock AS stock_4,
  t0.image AS image_5,
  t0.price AS price_6,
  t0.promotion AS promotion_7,
  t0.updated_at AS updated_at_8,
  t0.idSection AS idSection_9
FROM
  product t0
ORDER BY
  t0.idSection ASC
```
Les sections ne sont pas requêtées.

### Le problème des associations

[Doctrine extra lazy associations](https://www.doctrine-project.org/projects/doctrine-orm/en/2.11/tutorials/extra-lazy-associations.html)

Souvent, les associations entre entités peuvent devenir assez importantes. Dans Doctrine ORM, si on accéde à une association, elle sera toujours chargée complètement en mémoire. Cela peut entraîner des problèmes de performances assez sérieux, si nos associations contiennent plusieurs centaines ou milliers d'entités.

Doctrine ORM inclut une fonctionnalité appelée `Extra Lazy` pour les associations. Elles sont `Lazy` par défaut, ce qui signifie que l'ensemble de l'objet collection d'une association est rempli la première fois qu'il est accédé. Si on marque une association comme `Extra Lazy`, les méthodes suivantes sur les collections peuvent être appelées sans déclencher un chargement complet de la collection :

- Collection#contains($entity)
- Collection#containsKey($key)
- Collection#count()
- Collection#get($key)
- Collection#slice($offset, $length = null)

### Exemple pour l'affichage des produits

Le chargement de la page `/products` a besoin des entités suivantes :
- Product
- Section

Hors toute les associations de `products` suivantes sont chargées :

- Section
- Baskets
- Purchases
- orderDetails

On va donc ajouter sur l'annotation `fetch="EXTRA_LAZY"` sur les collections `baskets`, `Purchases` et `Purchasedetail`.


