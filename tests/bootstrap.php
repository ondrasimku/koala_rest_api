<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__) . '/.env');
}

$commands = [
    "cache:clear --no-warmup",
];

foreach ($commands as $command) {
    passthru(sprintf(
        'APP_ENV=%s php "%s/../bin/console" %s',
        $_ENV['APP_ENV'],
        __DIR__,
        $command
    ));
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}
