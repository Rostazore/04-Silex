<?php


use Silex\Application;
use TechNews\Provider\NewsControllerProvider;
use TechNews\Provider\AdminControllerProvider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Idiorm\Silex\Provider\IdiormServiceProvider;
use Silex\Provider\AssetServiceProvider;
use TechNews\Extensions\TechNewsTwigExtension;

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

# 5.1 Ajout des Extensions TechNews pour Twig (Accroche et Slugify).
$app->extend('twig', function ($twig, $app)
{
    $twig->addExtension(new TechNewsTwigExtension());
    return $twig;
});

# 5.2 Activation de Asset.
$app->register(new AssetServiceProvider());

# 6. Doctrine DBAL et Idiorm.
$app->register(new DoctrineServiceProvider(), [
    'db.options' => [
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'technews-denain',
        'user'      => 'root',
        'password'  => ''
    ]
]);
$app->register(new IdiormServiceProvider(), [
    'idiorm.db.options' => [
        'connection_string'    => 'mysql:host=localhost;dbname=technews-denain',
        'username'      => 'root',
        'password'  => '',
        'id_column_overrides' => [
                'view_articles' => 'IDARTICLE'
        ]
    ]
]);

# 6.1 Récupération des catégories.
$app['tn_catégories'] = function () use($app) {
    return $app['db']->fetchAll('SELECT * FROM categorie');
};

# 6.2 Récupération des tags.
$app['tn_tags'] = function () use($app) {
    return $app['db']->fetchAll('SELECT * FROM tags');
};

# 6.3 Récupération des catégories avec Idiorm.
$app['idiorm_catégories'] = function () use($app) {
    return $app['idiorm.db']->for_table('categorie')->find_result_set();
};

# 7. Execution de l'Application.
$app->run();