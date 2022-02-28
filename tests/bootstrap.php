<?php

/**
 * In order to load in Sniffs correctly, we need to use CodeSniffer's own
 * autoloader. To accomplish this whilst testing, we use this bootstrap
 * file in the phpunit.xml configuration rather than the standard.
 */

declare(strict_types=1);

error_reporting(E_ALL);

if (! function_exists('dd')) {
    function dd(mixed $value): void
    {
        var_dump($value);

        exit(1);
    }
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/squizlabs/php_codesniffer/autoload.php';