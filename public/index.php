<?php
// Define Root Path
define('ROOT_PATH', dirname(__DIR__));

// Autoloader
spl_autoload_register(function ($className) {
    // Define potential directories for classes
    $directories = [
        'app/core/',
        'app/controllers/',
        'app/models/'
    ];

    foreach ($directories as $directory) {
        $file = ROOT_PATH . '/' . $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Init Router
$router = new Router();
