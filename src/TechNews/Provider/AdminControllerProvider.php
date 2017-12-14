<?php


namespace TechNews\Provider;


use Silex\Application;
use Silex\Api\ControllerProviderInterface;


class AdminControllerProvider implements ControllerProviderInterface
{

    public function connect (Application $app)
    {
        # Récupérer l'instance de Silex\ControllerCollection.
        $controllers = $app['controllers_factory'];

        # Ajouter un article dans la BDD.
        $controllers->get('article/ajouter', 'TechNews\Controller\AdminController::addarticleAction')
            ->bind('admin_addarticle');
        return $controllers;
    }
    



}