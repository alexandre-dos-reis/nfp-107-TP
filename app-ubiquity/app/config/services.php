<?php

\Ubiquity\cache\CacheManager::startProd($config);
\Ubiquity\orm\DAO::start();

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'routes.php';

//\Ubiquity\assets\AssetsManager::start($config);