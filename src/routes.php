<?php
/**
 * Gestion des Routes de l'Application.
 */


use TechNews\Provider\NewsControllerProvider;
use TechNews\Provider\AdminControllerProvider;


# 4. Gestion de nos Controllers.
$app->mount('/', new NewsControllerProvider());
$app->mount('/admin', new AdminControllerProvider());