<?php
// Autoload class
namespace App;

class Autoloader
{
    // Register autoloader
    public static function register()
    {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }
    public static function autoload($class)
    {
        $classe = str_replace(__NAMESPACE__ . '\\', '', $class);
        $classe = str_replace('\\', '/', $classe);
        $file = __DIR__ . '/' . $classe . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            echo "Le fichier Fichier n'existe pas";
        }
    }
}
