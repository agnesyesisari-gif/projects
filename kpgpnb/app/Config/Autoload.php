<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Namespaces
     * -------------------------------------------------------------------
     */
    public $psr4 = [
        APP_NAMESPACE => APPPATH, // For custom app namespace
        'Config'      => APPPATH . 'Config',
        'App'         => APPPATH,
        'Modules'     => APPPATH . 'Modules', // Jika menggunakan modular
    ];

    /**
     * -------------------------------------------------------------------
     * Class Map
     * -------------------------------------------------------------------
     */
    public $classmap = [];

    public $files = [
        APPPATH . 'Helpers/DataHelper.php',
        APPPATH . 'Helpers/FormatHelper.php',
        APPPATH . 'Helpers/PathHelper.php',
    ];

    public $helpers = [];
}