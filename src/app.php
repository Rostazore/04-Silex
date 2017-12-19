<?php


use Silex\Provider\TwigServiceProvider;

use Silex\Provider\AssetServiceProvider;
use TechNews\Extensions\TechNewsTwigExtension;
use Silex\Provider\HttpFragmentServiceProvider;


# 1. Activation du débuggage.
$app['debug'] = true;

# 2. Gestion des routes.
require PATH_SRC . '/routes.php';

# 3. Activation de twig.
# composer require twig/twig
$app->register(new TwigServiceProvider(), [
    'twig.path' => [
        __DIR__ . '/../ressources/views',
        __DIR__ . '/../ressources/layout'
    ]
]);

# 4 Ajout des Extensions TechNews pour Twig (Accroche et Slugify).
$app->extend('twig', function ($twig, $app)
{
    $twig->addExtension(new TechNewsTwigExtension());
    return $twig;
});

# 5 Activation de Asset.
$app->register(new AssetServiceProvider());

# 6. Permet le rendu d'un controller dans la vue.
$app->register(new HttpFragmentServiceProvider());

# 7. Configuration de la base de données.
require PATH_RESSOURCES . '/config/database.config.php';

# 8. Sécurisation de l'application.
require PATH_RESSOURCES . '/config/security.php';

return $app;