<?php
/**
 * Manual Autoloader for Endroid QRCode
 * Use if Composer is not available
 */

class ManualAutoloader
{
    public static function load($class)
    {
        // Handle Endroid namespace
        if (strpos($class, 'Endroid\\') === 0) {
            $path = str_replace('\\', '/', $class);
            $file = dirname(__DIR__) . '/vendor/endroid-qrcode/src/' . $path . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
        
        // Handle App namespace
        if (strpos($class, 'App\\') === 0) {
            $path = str_replace('\\', '/', substr($class, 4));
            $file = dirname(__DIR__) . '/src/' . $path . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
}

spl_autoload_register(['ManualAutoloader', 'load']);