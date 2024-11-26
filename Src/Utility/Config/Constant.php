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

    // Private constructor untuk mencegah pembuatan instance dari luar class
    private function __construct()
    {}

}
