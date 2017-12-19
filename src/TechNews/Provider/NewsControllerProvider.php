<?php


namespace TechNews\Provider;


use Silex\Application;
use Silex\Api\ControllerProviderInterface;


class NewsControllerProvider implements ControllerProviderInterface
{
    /**
     * 
     */
    public function connect (Application $app)
    {
        # Récupérer l'instance de Silex\ControllerCollection.
        # https://silex.symfony.com/doc/2.0/organizing_controllers.html
        # 
        $controllers = $app['controllers_factory'];

            # Page d'Accueil.
            $controllers
                # On associe une route à un Controller et une Action.
                ->get('/', 'TechNews\Controller\NewsController::indexAction')
                # En option, je peux donner un nom à la route, qui servira
                # plus tard pour la création de liens.
                ->bind('news_index');

            # Page Catégorie.
            $controllers
                ->get('/categorie/{libelle}',
                    'TechNews\Controller\NewsController::catégorieAction')
                # Je spécifie le type de paramètre attendu avec une Regex.
                ->assert ('libelle', '[^/]+')
                # Je peux attribuer une valeur par défaut.
                ->value('libelle', 'computer')
                # Nom de ma route.
                ->bind('news_categorie');

            # Page Article.
            $controllers
            ->get('/{categorie_libelle}/{article_slug}_{article_id}.html',
                'TechNews\Controller\NewsController::articleAction')
            ->assert('idarticle', '\d+')
            ->bind('news_article');

            # Page d'inscription.
            $controllers
            ->get('/inscription.html', 'TechNews\Controller\NewsController::inscriptionAction')
            ->bind('news_inscription');
            $controllers
            ->post('/inscription.html', 'TechNews\Controller\NewsController::inscriptionPost')
            ->bind('news_inscription_post');
            # Page de connexion.
            $controllers
            ->get('/connexion.html', 'TechNews\Controller\NewsController::connexionAction')
            ->bind('news_connexion');
            $controllers
            ->post('/connexion.html', 'TechNews\Controller\NewsController::connexionAction')
            ->bind('news_connexion_post');
            # Page de déconnexion.
            $controllers
            ->get('/deconnexion.html', 'TechNews\Controller\NewsController::deconnexionAction')
            ->bind('news_deconnexion');

            # PHP Info.
            $controllers
                ->get('/infos', [$this, 'infoAction']);

        return $controllers;
    }

    public function infoAction ()
    {
        return phpinfo();
    }
}