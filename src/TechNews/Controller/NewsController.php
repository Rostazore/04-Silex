<?php


namespace TechNews\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
    public function articleAction ($categorie_libelle, $article_slug, $article_id, Application $app)
    {
        # index.php/business/une-formation-innovante-a-denain_666.html
        # Récupération de l'article.
        $article = $app['idiorm.db']->for_table('view_articles')->find_one($article_id);

        # Récupération des Articles de la catégorie de l'article (suggestion).
        # Je récupère uniquement, les articles de la même catégorie que mon article.
        $articles = $app['idiorm.db']->for_table('view_articles')->where('IDCATEGORIE', $article->IDCATEGORIE)

        # Sauf mon article en cours.
            ->where_not_equal('IDARTICLE', $article_id)

        # 3 articles maximum.
            ->limit(3)

        # Par ordre décroissant.
            ->order_by_desc('IDARTICLE')

        # Je récupère les résultats.
            ->find_result_set();
        
        return $app['twig']->render ('article.html.twig', [
            "article"     => $article,
            "suggestions"    => $articles
        ]);
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

    /**
     * Affichage de la page d'inscription.
     * @param Application $app
     * return \Symfony
     */
    public function inscriptionAction (Application $app)
    {
        return $app['twig']->render('inscription.html.twig');
    }

    /**
     * Traitement POST du formulaire d'inscription.
     */
    public function inscriptionPost (Application $app, Request $request)
    {
        # Vérification et sécurisation des données POST.
        # ...

        # Connexion à la BDD.
        $auteur = $app['idiorm.db']->for_table('auteur')->create();

        # Affectation de valeurs.
        $auteur->PRENOMAUTEUR = $request->get('PRENOMAUTEUR');
        $auteur->NOMAUTEUR = $request->get('NOMAUTEUR');
        $auteur->EMAILAUTEUR = $request->get('EMAILAUTEUR');
        $auteur->MDPAUTEUR = $app['security.default_encoder']->encodePassword($request->get('MDPAUTEUR'), '');
        $auteur->ROLEAUTEUR = 'ROLE_MEMBRE';

        # On persiste en BDD.
        $auteur->save();

        # On envoie un email de confirmation ou de bienvenue…
        # On envoie une notification à l'administrateur:
        # …

        # On redirige l'utilisateur sur la page de connexion.
        return $app->redirect('connexion.html?inscription=success');
    }

    /**
     * Affichage de la page de connexion.
     * @param Application   $app
     * @param Request       $request
     * return \Symfony
     */
    public function connexionAction (Application $app, Request $request)
    {
        return $app['twig']->render('connexion.html.twig', [
            'error'         => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        ]);
    }
}