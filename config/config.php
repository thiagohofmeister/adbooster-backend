<?php

use Interop\Container\ContainerInterface;
use MongoDB\Database;

return [
    Database::class => function (ContainerInterface $container) {
        return (new MongoDB\Client(getenv('MONGO_URI')))
            ->selectDatabase(getenv('MONGO_DATABASE'));
    },

    'settings.displayErrorDetails' => true,
];
