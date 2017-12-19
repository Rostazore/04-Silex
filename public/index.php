<?php


use Silex\Application;


# 1. Quelques constantes utiles.
define('PATH_ROOT', dirname(__DIR__));
define('PATH_PUBLIC', PATH_ROOT . '/public');
define('PATH_SRC', PATH_ROOT . '/src');
define('PATH_RESSOURCES', PATH_ROOT . '/ressources');
define('PATH_VIEWS', PATH_RESSOURCES . '/views');
define('PATH_VADOR', PATH_ROOT . '/vendor');

# 2. Importation de l'autoload.
require_once PATH_VADOR . '/autoload.php';

# 3. Instanciation de l'Application.
$app = new Application();

# 4. Configuration de l'Application.
require_once PATH_SRC . '/app.php';

# 5. Execution de l'Application.
$app->run();