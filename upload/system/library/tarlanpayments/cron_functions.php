<?php

function tarlanpayments_validate()
{
    if (php_sapi_name() != 'cli') {
        die('Not in Command Line.');
    }
}

function tarlanpayments_chdir($current_dir)
{
    $root_dir = dirname(dirname(dirname($current_dir)));

    chdir($root_dir);

    return $root_dir;
}

function tarlanpayments_define_route()
{
    define('TARLANPAYMENTS_ROUTE', 'extension/recurring/tarlanpayments/recurring');

    $_GET['route'] = TARLANPAYMENTS_ROUTE;
}

function tarlanpayments_init($current_dir)
{
    global $argc, $argv;

    // Validate environment
    tarlanpayments_validate();

    // Set up default server vars
    if (isset($argc) && isset($argv) && $argc >= 3) {
        $_SERVER['HTTP_HOST'] = $argv[1];
        $_SERVER['SERVER_NAME'] = $argv[1];
        $_SERVER['SERVER_PORT'] = $argv[2];
    } else {
        $_SERVER['HTTP_HOST'] = 'localhost';
        $_SERVER['SERVER_NAME'] = 'localhost';
        $_SERVER['SERVER_PORT'] = 80;
    }

    putenv('SERVER_NAME=' . $_SERVER['SERVER_NAME']);

    // Change root dir
    $root_dir = tarlanpayments_chdir($current_dir);

    tarlanpayments_define_route();

    if (file_exists($root_dir . '/index.php')) {
        return $root_dir . '/index.php';
    }
}
