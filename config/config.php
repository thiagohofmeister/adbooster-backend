<?php

use Interop\Container\ContainerInterface;
use MongoDB\Database;
use App\Core;

return [
    Database::class => function (ContainerInterface $container) {
        return (new MongoDB\Client(getenv('MONGO_URI')))
            ->selectDatabase(getenv('MONGO_DATABASE'));
    },

    'upload.basePath' => function (ContainerInterface $container) {

        return __DIR__ . DIRECTORY_SEPARATOR . '../public/uploads/';
    },

    'upload.link' => function (ContainerInterface $container) {

        $environment = $container->get(Core\Enum\Environment::class);

        if ($environment->value() == Core\Enum\Environment::DEVELOPMENT) {
            return 'http://localhost:3001/image/';
        }

        return 'https://www.adbooster.com.br/image/';
    },

    'settings.displayErrorDetails' => true,
];
