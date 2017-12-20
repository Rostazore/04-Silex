<?php


use Silex\Provider\DoctrineServiceProvider;
use Idiorm\Silex\Provider\IdiormServiceProvider;


# 5. Doctrine DBAL et Idiorm.
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
                'view_articles' => 'IDARTICLE',
                'categorie'     => 'IDCATEGORIE'
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