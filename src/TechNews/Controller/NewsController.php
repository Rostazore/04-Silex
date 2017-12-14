<?php


namespace TechNews\Controller;


use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class NewsController
{
    /**
     * Affichage de la page d'accueil.
     * @param Application $app
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function indexAction (Application $app)
    {
        return $app['twig']->render('index.html.twig');
    }

    /**
     * Affichage des articles d'une catégorie.
     * @param $libellé
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function catégorieAction ($libelle)
    {
        return "<h1>Catégorie : $libelle</h1>";
    }

    /**
     * Affichage de la page Article.
     * @param $catégorie_libellé
     * @param $article_slug
     * @param $article_id
     * return Symfony\Component\HttpFoundation\Response
     */
    public function articleAction ($categorie_libelle, $article_slug, $article_id)
    {
        # index.php/business/une-formation-innovante-a-denain_666.html
        return "<h1>Article n°$article_id | $article_slug</h1>";
    }
}