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
        # Récupération des Articles.
        $articles = $app['idiorm.db']->for_table('view_articles')->find_result_set();

        # Récupération des Articles en spotlight.
        $spotlights = $app['idiorm.db']->for_table('view_articles')->where('SPOTLIGHTARTICLE', 1)->find_result_set();

        # Affichiation de la vue.
        return $app['twig']->render('index.html.twig', [
            'articles'      => $articles,
            'spotlights'    => $spotlights
        ]);
    }

    /**
     * Affichage des articles d'une catégorie.
     * @param $libellé
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function catégorieAction ($libelle, Application $app)
    {
        # Récupération des Articles de la Catégorie.
        $articles = $app['idiorm.db']->for_table('view_articles')
            ->where('LIBELLECATEGORIE', ucfirst($libelle))
            ->find_result_set();

        # Affichiation de la vue.
        return $app['twig']->render('categorie.html.twig', [
            'articles'      => $articles,
            'libelle'       => $libelle
        ]);
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

    /**
     * Génération du Menu dans la layout.
     * @param Application $app
     */
    public function menu (Application $app)
    {
        # Récupération des catégories.
        $catégories = $app['idiorm.db']->for_table('categorie')->find_result_set();

        # Transmission à la vue.
        return $app['twig']->render('menu.html.twig', [
            'categories'    => $catégories
        ]);
    }

    /**
     * Génération de la Sidebar dans 
     * @param Application $app
     */
    public function sidebar (Application $app)
    {
        # Récupération des articles.
        $articles = $app['idiorm.db']->for_table('view_articles')
            ->limit(5)
            ->order_by_desc('DATECREATIONARTICLE')
            ->find_result_set();

        # Récupération des articles spéciaux.
        $spéciaux = $app['idiorm.db']->for_table('view_articles')
            ->where('SPECIALARTICLE', 1)
            ->find_result_set();

        # Transmission à la vue.
        return $app['twig']->render('sidebar.html.twig', [
            'articles'  => $articles,
            'spéciaux'  => $spéciaux
        ]);
    }
}