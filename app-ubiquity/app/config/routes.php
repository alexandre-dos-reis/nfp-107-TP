<?php

use Ubiquity\controllers\Router;
use controllers\ProductController;
use controllers\PurchaseController;

Router::start();

Router::addRoute("_default", ProductController::class, 'index', null, "home");

// Router::addRoute("/products", ProductController::class, 'index', null, "products.index");
// Router::addRoute("/products/{id}", ProductController::class, 'detail', null, 'products.detail');

// Router::addRoute("/purchases", PurchaseController::class, 'index', null, "purchases.index");
// Router::addRoute("/purchases/{id}", PurchaseController::class, 'detail', null, 'purchases.detail');
