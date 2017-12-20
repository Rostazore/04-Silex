<?php


namespace TechNews;


trait Helper
{
    /**
     * Génère un SLUG en PHP à partir d'un string.
     * @param $text string
     * @return string Slug
     * @see https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
     */
    public function slugify ($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
  
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
    }

}