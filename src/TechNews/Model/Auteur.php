<?php


namespace TechNews\Model;


use Symfony\Component\Security\Core\User\UserInterface;


class Auteur implements UserInterface
{
    # Définition des propriétés.
    private $IDAUTEUR,
            $NOMAUTEUR,
            $PRENOMAUTEUR,
            $EMAILAUTEUR,
            $MDPAUTEUR,
            $ROLEAUTEUR;

    /**
     * Constructeur.
     */
    public function __construct($IDAUTEUR, $NOMAUTEUR, $PRENOMAUTEUR, $EMAILAUTEUR, $MDPAUTEUR, $ROLEAUTEUR)
    {
        $this->IDAUTEUR = $IDAUTEUR;
        $this->NOMAUTEUR = $NOMAUTEUR;
        $this->PRENOMAUTEUR = $PRENOMAUTEUR;
        $this->EMAILAUTEUR = $EMAILAUTEUR;
        $this->MDPAUTEUR = $MDPAUTEUR;
        $this->ROLEAUTEUR[] = $ROLEAUTEUR;
    }

    public function getIDAUTEUR ()
    {
        return $this->IDAUTEUR;
    }
    
    public function getNOMAUTEUR()
    {
        return $this->NOMAUTEUR;
    }

    public function getPRENOMAUTEUR()
    {
        return $this->PRENOMAUTEUR;
    }
    
    public function getUsername()
    {
        return $this->EMAILAUTEUR;
    }

    public function getPassword()
    {
        return $this->MDPAUTEUR;
    }

    public function getRoles()
    {
        return $this->ROLEAUTEUR;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        return null;
    }
}