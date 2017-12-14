<?php


use Silex\Application;
use TechNews\Provider\NewsControllerProvider;
use TechNews\Provider\AdminControllerProvider;
use Silex\Provider\TwigServiceProvider;

# 1. Importation de l'autoload.
require_once __DIR__ . '/../vendor/autoload.php';

# 2. Instanciation de l'Application.
$app = new Application();

# 3. Activation du débuggage.
$app['debug'] = true;

# 4. Gestion de nos Controllers.
$app->mount('/', new NewsControllerProvider());
$app->mount('/admin', new AdminControllerProvider());

# 5. Activation de twig.
# composer require twig/twig
$app->register(new TwigServiceProvider(), [
    'twig.path' => [
        __DIR__ . '/../ressources/views',
        __DIR__ . '/../ressources/layout'
    ]
]);

# 6. Execution de l'Application.
$app->run();