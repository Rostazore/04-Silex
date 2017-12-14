<?php


use Silex\Application;


/**
 * 1. Importation de l'autoload de Composer.
 * Il permet de charger toutes les dépendances du
 * projet de façon automatique. Ex. Symfony, …
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * 2. Instanciation de l'application Silex.
 * @var \Silex\Application $app
 */
$app = new Application();

/**
 * 3. Activation du Debuggage.
 */
$app['debug'] = true;

/**
 * J'associe la route «/» à ma fonction anonyme
 * qui me renvoi le résultat à afficher.
 */
$app->get('/', function () {
    return "<h1>Page d'Accueil</h1>";
});

/**
 * «match» : $app->match() permet de ne pas spécifier
 * de type de méthode (get, post, …).
 * «method» : $app->method() permet de n'autoriser
 * que certaines méthodes HTTP/.
 */
$app->match('/ave/{prenom}', function ($prenom) {
    return new Response ("<h1>Ave $prenom</h1>");
})
    ->method('GET|POST')
    ->value('prenom', 'Serva');

$app['prenom_visiteur'] = "Raoul";

/*$app->match('/salut/{prenom}', function ($prenom) {
    return "<h1>Salut $prenom</h1>";
})
->method('GET|POST')
  ->value('prenom', $app['prenom_visiteur']);*/

# Affiche : Bonjour 76 76
# Une fois instancié, le DIC va retourner la même instance.
/*$app['prenom_rand'] = function () {
    return rand(1, 100);
};*/

# Affiche : Bonjour 15 52
# La fonction «factory» ordonne à PIMPLE de créer une nouvelle instance
# de la classe / fonction.
/*$app['prenom_rand'] = $app->factory(function () {
    return rand(1, 100);
});*/

# La fonction «protect» empêche l'exécution de la fonction par PIMPLE.
# Je vais pouvoir l'exécuter moi-même avec «()».
$app['prenom_rand'] = $app->protect(function () {
    return rand(1, 100);
});

$app->match('/salut/{prenom}', function ($prenom, Application $app) {
    return "<h1>Salut $prenom ". $app['prenom_rand']() . "</h1>";
})
    ->method('GET|POST')
    ->value('prenom', $app['prenom_rand']());

/**
 * Exécution de Silex.
 */
$app->run();