<?php


namespace TechNews\Controller;


use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;


class AdminController
{
    use \TechNews\Helper;


    public function addarticleAction (Application $app, Request $request)
    {
        # Récupération de la liste des catégories.
        $catégories = function () use ($app)
        {
            # Récupération des catégories dans la BDD.
            $catégories = $app['idiorm.db']->for_table('categorie')->find_result_set();

            # On formate l'affichage pour le champ select (ChoiceType).
            $array = [];
            foreach ($catégories as $catégorie) :
                $array[$catégorie->LIBELLECATEGORIE] = $catégorie->IDCATEGORIE;
            endforeach;

            # On retourne le tableau.
            return $array;
        };

        # Créer un formulaire permettant l'ajout d'un article.
        $form = $app['form.factory']->createBuilder(FormType::class)
            # Champ TITREARTICLE
            ->add('TITREARTICLE', TextType::class, [
                'required'      => true,
                'label'         => false,
                'constraints'   => [new NotBlank()],
                'attr'          => [
                    'class'         => 'form-control',
                    'placeholder'   => 'Titre de  l\'article…'
                ]
            ])
            # Champ IDCATEGORIE
            ->add('IDCATEGORIE', ChoiceType::class, [
                'choices'   => $catégories(),
                'expanded'  => false,
                'multiple'  => false,
                'label'     => false,
                'attr'      => [
                    'class'     => 'form-control'
                ]
            ])
            # Champ CONTENUARTICLE
            ->add('CONTENUARTICLE', TextareaType::class, [
                'required'      => true,
                'label'         => false,
                'constraints'   => [new NotBlank()],
                'attr'          => [
                    'class'         => 'form-control',
                ]
            ])
            # Champ FEATUREDIMAGEARTICLE
            ->add('FEATUREDIMAGEARTICLE', FileType::class, [
                'required'      => false,
                'label'         => false,
                'attr'          => [
                    'class'     => 'dropify'
                ]
            ])
            # Champs SPECIALARTICLE et SPOTLIGHTARTICLE
            ->add('SPECIALARTICLE', CheckboxType::class, [
                'required'      => false,
                'label'         => false,
            ])
            ->add('SPOTLIGHTARTICLE', CheckboxType::class, [
                'required'      => false,
                'label'         => false,
            ])
            # Bouton pour soumettre le villain bouton.
            ->add('submit', SubmitType::class, ['label' => 'Publicationner'])

            /**
             * Maintenant que tous les champs ont été créés, nous allons pouvoir
             * récupérer le formulaire.
             */
            ->getForm();

            # Traitement des données POST.
            $form->handleRequest($request);

            # Vérification des données du formulaire.
            if ($form->isValid()):

                # Récupération des données.
                $article = $form->getData();

                # Récupération de l'image.
                $image = $article['FEATUREDIMAGEARTICLE'];
                $chemin = PATH_PUBLIC . '/images/product';
                $image->move($chemin, $this->slugify($article->TITREARTICLE) . '.jpg');

                # Récupération de l'auteur.
                $token = $app['security.token_storage']->getToken();
                if (null != $token)
                    $auteur = $token->getUser();
                else
                    return $app->redirect('/');

                # Insertion en BDD.
                $articleBdd = $app['idiorm.db']->for_table('article')->create();

                $categorie = $app['idiorm.db']->for_table('categorie')->find_one($article['IDCATEGORIE']);

                # On associe les colonnes de notre BDD avec les valeurs du formulaire.
                # Colonne mysql                              # valeur du formulaire
                $articleBdd->IDAUTEUR = $auteur->getIDAUTEUR();
                $articleBdd->IDCATEGORIE = $article['IDCATEGORIE'];
                $articleBdd->TITREARTICLE = $article['TITREARTICLE'];
                $articleBdd->CONTENUARTICLE = $article['CONTENUARTICLE'];
                $articleBdd->SPECIALARTICLE = $article['SPECIALARTICLE'];
                $articleBdd->SPOTLIGHTARTICLE = $article['SPOTLIGHTARTICLE'];
                $articleBdd->FEATUREDIMAGEARTICLE = $this->slugify($article['TITREARTICLE']) . '.jpg';

                # Insertion en BDD.
                $articleBdd->save();

                # Redirection sur l'article qui vient d'être créé.
                return $app->redirect($app['url_generator']->generate(
                    'news_article', [
                        'libellecategorie'  => strtolower($categorie->LIBELLECATEGORIE),
                        'slugarticle'       => $this->slugify($article['TITREARTICLE']),
                        'idarticle'         => $articleBdd->IDARTICLE,
                    ]
                ));

            endif;

            # Affichage du formulaire dans la vue.
        return $app['twig']->render('admin/ajouterarticle.html.twig', ['form' => $form->createView()]);
    }
}