<?php
namespace App\Src\Utility\Config;

class Constant
{

    // web config
    public const Secret_Key = 'a6000edf-92d8-47da-a239-4042ddae573d';
    public const Domain = 'metrobalim.com';

    public const AppName = "Metrobalim";
    public const Version = "2.0.0";
    public const SUCCESS_STATUS = "Success";
    public const ERROR_STATUS = "Error";

    // Header Token Response
    public const TOKEN_INVALID = "TOKEN_INVALID";
    public const TOKEN_EXPIRED = "TOKEN_EXPIRED";
    public const TOKEN_REVOKED = "TOKEN_REVOKED";
    public const TOKEN_MISSING = "TOKEN_MISSING";

    // Folder Paths
    public const ImagePath = __DIR__ . '/../../../public/images/original';
    public const ImageThumbnailPath = __DIR__ . '/../../../public/images/thumbnails';

    public const ImageRatioFeatured_Width = 1280; // 16:9featured
    public const ImageRatioFeatured_Height = 720;

    public const ImageRatioArticle_Width = 800; // 4:3 thumbnail article
    public const ImageRatioArticle_Height = 600;

    public const ImageRatioSmall_Width = 320; // 4:3 small thumbnail article
    public const ImageRatioSmall_Height = 240;

    /*
    ImageCacheAge in second
    sample 31536000
    1 day = 24 hours x 60 minutes x 60 seconds = 86.400 seconds
    31.536.000 seconds ÷ 86.400 seconds/day = 365 hours
    So,31536000 = 1 Year.

    another sample

    1 month: 30 * 24 * 60 * 60 = 2.592.000 seconds
    1 week: 7 * 24 * 60 * 60 = 604.800 seconds
    1 hour: 1 * 60 * 60 = 3600 seconds
     */
    public const ImageCacheAge = '31536000';

    // Private constructor untuk mencegah pembuatan instance dari luar class
    private function __construct()
    {}

}
