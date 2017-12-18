<?php


namespace TechNews\Extensions;


class TechNewsTwigExtension extends \Twig_Extension
{
    /**
     * Création des Filters Accroche et "Slugify".
     * @return array|\Twig_Filter[]|void
     */
    public function getFilters ()
    {
        return [
            new \Twig_Filter('accroche', function ($texte) {
                # Supprimer toutes les balises HTML.
                $string = strip_tags ($texte);

                # Si ma chaine de caractère est supérieur à 170
                # je poursuis, sinon c'est inutile.
                if (strlen($string) > 170) :
                    # Je coupe la chaine à 170.
                    $stringCut = substr ($string, 0, 170);

                    # Je m'assure que je ne coupe pas de mot !
                    $string = substr ($stringCut, 0, strrpos ($stringCut, ' ')) . '…';
                endif;

                # On retourn l'accroche.
                return $string;
            }), # Fin du filtre Twig Accroche
            new \Twig_Filter('slugify', function ($texte) {
                // replace non letter or digits by -
                $text = preg_replace('~[^\pL\d]+~u', '-', $texte);
  
                // transliterate
                $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  
                // remove unwanted characters
                $text = preg_replace('~[^-\w]+~', '', $text);
  
                // trim
                $text = trim($text, '-');
  
                // remove duplicate -
                $text = preg_replace('~-+~', '-', $text);
  
                // lowercase
                $text = strtolower($text);
  
                if (empty($text)) {
                    return 'n-a';
                }
  
                return $text;
            })
        ]; # Fin du Array.
    } # Fin de getFilters.
} # Fin de la classe TechNewsTwigExtension.