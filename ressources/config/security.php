<?php


use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\SessionServiceProvider;
use TechNews\Provider\AuteurProvider;


$app->register(new SessionServiceProvider());

$app->register(new SecurityServiceProvider(), [
    /**
     * Ici je crée mon firewall pour l'application.
     */
    'security.firewalls'        => [
        'principal' => [
            'pattern'   => '^/',
            'http'      => 'true',
            'anonymous' => 'true',
            'form'      => [
                'login_path'    => '/connexion.html',
                'check_path'    => '/connexion.html/login_check',
            ],
            'logout'    => [
                'logout_path'   => '/deconnexion.html'
            ],
            'users'     => function () use ($app) {
                return new AuteurProvider($app['idiorm.db']);
            }
        ]
    ],
    /**
     * Je définis mes règles d'accès, à savoir,
     * quelles routes pour quels rôles.
     */
    'security.access_rules'     => [
        ['^/admin', 'ROLE_ADMIN', 'http'],
        ['^/auteur', 'ROLE_AUTEUR', 'http'],
    ],
    /**
     * Je définis la hiérarchie d'accès.
     * Ex. un ROLE_ADMIN à aussi un ROLE_AUTEUR.
     */
    'security.role.hierarchy'   => [
        'ROLE_ADMIN' => ['ROLE_AUTEUR']
    ]
]);