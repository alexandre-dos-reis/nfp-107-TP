<?php

use Ubiquity\controllers\Router;
use controllers\ProductController;
// use controllers\PurchaseController;

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();

//require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'routes.php';

Router::start();

Router::addRoute("_default", ProductController::class, 'index', null, "home");

//\Ubiquity\assets\AssetsManager::start($config);