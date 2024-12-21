<?php

namespace App\Src\Utility\Helper;

class UtilityHelper
{
    public static function slugify($text)
    {
        // Implementasi custom untuk membuat slug
        return strtolower(preg_replace('~[^\w]+~', '-', $text));
    }

}
