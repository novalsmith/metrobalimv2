<?php
namespace App\Src\Utility\Config;

class Constant
{

    // Contoh constant atau property yang ingin diakses secara global
    public const AppName = "Metrobalim";
    public const Version = "2.0.0";
    public const SUCCESS_STATUS = "Success";
    public const ERROR_STATUS = "Error";

    // Private constructor untuk mencegah pembuatan instance dari luar class
    private function __construct()
    {}

}
