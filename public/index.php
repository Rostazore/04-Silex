<?php


use Silex\Application;
use TechNews\Provider\NewsControllerProvider;

# 1. Importation de l'autoload.
require_once __DIR__ . '/../vendor/autoload.php';

# 2. Instanciation de l'Application.
$app = new Application();

# 3. Activation du débuggage.
$app['debug'] = true;

# 4. Gestion de nos Controllers.
$app->mount('/', new NewsControllerProvider());

# 5. Execution de l'Application.
$app->run();