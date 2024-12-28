<?php

namespace App\Src\Utility\Helper;

use App\Src\Utility\Config\Constant;

class UtilityHelper
{
    public static function slugify($text)
    {
        // Implementasi custom untuk membuat slug
        return strtolower(preg_replace('~[^\w]+~', '-', $text));
    }

    public static function imagePath(string $imageName)
    {
        return  Constant::publicPath.Constant::ImagePath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $imageName;
    }

    public static function imageThumbPath(string $imageName)
    {
        return Constant::publicPath.Constant::ImageThumbnailPath . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . $imageName;
    }

}
