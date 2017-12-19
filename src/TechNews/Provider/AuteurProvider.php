<?php


namespace TechNews\Provider;


use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use TechNews\Model\Auteur;


class AuteurProvider implements UserProviderInterface
{
    private $bdd;

    /**
     * Récupération de l'instance de la base de données.
     * @param $bdd  Idiorm ou Doctrine DBAL.
     */
    public function __construct ($bdd)
    {
        $this->bdd = $bdd;
    }

    public function supportsClass($class)
    {
        return $class === 'TechNews\Model\Auteur';
    }

    public function refreshUser(UserInterface $auteur)
    {
        # On s'assure de bien avoir un Objet Auteur.
        if (!$auteur instanceof Auteur) :
            throw new UnsupportedUserException (
                sprintf ('Les instances de "%s" ne sont pas autorisées.', getClass($auteur))
            );
        endif;

        # Si tous est correct, je peux charger l'utilisateur via son username.
        return $this->loadUserByUsername($auteur->getUsername());
    }

    public function loadUserByUsername($EMAILAUTEUR)
    {
        # Je récupère l'auteur par rapport à son username.
        $auteur = $this->bdd->for_table('auteur')->where('EMAILAUTEUR', $EMAILAUTEUR)->find_one();

        # Je vérifie que l'utilisateur existe.
        if (empty ($auteur)) :
            throw new UserNotFoundException (
                sprintf ("Cet utilisateur «%s» n'existe pas.", $EMAILAUTEUR)
            );
        endif;

        # Si tout est bon, je retourne une instance de Auteur.
        return new Auteur ($auteur->IDAUTEUR, $auteur->NOMAUTEUR, $auteur->PRENOMAUTEUR, $auteur->EMAILAUTEUR, $auteur->MDPAUTEUR, $auteur->ROLEAUTEUR);
    }
}