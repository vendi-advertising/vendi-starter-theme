<?php

if (!is_readable(VENDI_CUSTOM_THEME_PATH . '/vendor/autoload.php')) {
    throw new RuntimeException('Please make sure that `composer install` has been run');
}

require_once VENDI_CUSTOM_THEME_PATH . '/vendor/autoload.php';

// Boot env support
// In order to use putenv/getnev with Symfony 5 we have to pass true here
(new \Symfony\Component\Dotenv\Dotenv(true))->loadEnv(VENDI_CUSTOM_THEME_PATH . '/.env');

spl_autoload_register(
    static function ($class) {
        //PSR-4 compliant autoloader
        //See http://www.php-fig.org/psr/psr-4/
        $prefixes = [
            'Vendi\\Theme' => VENDI_CUSTOM_THEME_PATH . '/src/',
        ];
        foreach ($prefixes as $prefix => $base_dir) {
            // does the class use the namespace prefix?
            $len = strlen($prefix);
            if (0 !== strncmp($prefix, $class, $len)) {
                // no, move to the next registered prefix
                continue;
            }

            // get the relative class name
            $relative_class = substr($class, $len);
            // replace the namespace prefix with the base directory, replace namespace
            // separators with directory separators in the relative class name, append
            // with .php
            $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

            // if the file exists, require it
            if (file_exists($file)) {
                require_once $file;
            }
        }

    }
);
