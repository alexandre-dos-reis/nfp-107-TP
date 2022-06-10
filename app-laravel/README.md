# Laravel

## Import des modèles

- [Créer les models par une instrospection de la base de données](https://github.com/reliese/laravel)
- Installation du paquet avec `composer require reliese/laravel --dev`
- Configuration : `php artisan vendor:publish --tag=reliese-models`
- Suppression du cache : `php artisan config:clear`
- Introspection, convertion des tables en model : `php artisan code:models`

## ORM Eloquent

### Design pattern

Contrairement à l'ORM Doctrine qui utilise un pattern Data Mapper avec le principe de Respository, Eloquent utilise le pattern appelé Active Record.

À l'aide de l'approche Active Record, vous définissez toutes vos méthodes de requête à l'intérieur du modèle lui-même, et vous enregistrez, supprimez et chargez des objets à l'aide de méthodes de modèle.
En termes simples, le modèle Active Record est une approche pour accéder à votre base de données dans vos modèles.

### Transactions

Par défaut, [Eloquent n'utilise pas de transaction](https://laravel.com/docs/8.x/database#database-transactions), il faut donc spécifier lorsque l'on veut effectuer une transaction, comme ceci :

```php
DB::transaction(function () {
    // Création, mise à jour des modèles...
})
```

Voici l'implémentation de la méthode `pay` pour l'achat d'un panier :

```php
public function pay(CartService $cs): RedirectResponse
    {
        DB::transaction(function () use ($cs) {

            $faker = Factory::create();

            $p = new Purchase();

            $p->amount = $cs->getTotal();
            $p->dateCreation = new \DateTime();
            $p->itemsNumber = $cs->countProducts();
            $p->missingNumber = 0;
            $p->status = Purchase::STATUS_CREATED;
            $p->toPay = 0;
            $p->idUser = $faker->randomElement(User::all())->id;
            $p->idEmployee = $faker->randomElement(Employee::all())->id;
            $p->idTimeslot = $faker->randomElement(Timeslot::all())->id;

            $p->save();

            foreach ($cs->getDetailedCartItems() as $ci) {

                /**
                 * @var Product $product
                 */
                $product = Product::findOrFail($ci->product->id);
                $newStock = $product->stock - $ci->qty;

                if ($newStock < 0) {
                    redirect()
                        ->route('cart_index')
                        ->with("The quantity requested for the #{$product->id} {$product->name} exceeds the available stock !");
                }

                $product->stock = $newStock;
                $product->save();

                $pd = new Purchasedetail();
                $pd->idProduct = $ci->product->id;
                $pd->idOrder = $p->id;
                $pd->quantity = $ci->qty;
                $pd->prepared = 0;

                $pd->save();
            }
        });

        $cs->emptyCart();

        return redirect()
            ->route('cart_index')
            ->with('success', "Your payment has succeded, we are preparing your order.");
    }
```

Si un problème survenait pendant cette transaction, toutes les modifications seraient annulées et la base de données reviendrait à son état précédent.

## Implémentation 

### Recherche fulltext

Installation d'un plugin permettant de gérer les recherches fulltext :

```bash
composer require nicolaslopezj/searchable
```

