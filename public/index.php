<?php

use App\Core\App;
use App\Core\Enum\Environment;
use THS\Utils\Converter\Type\Json;

define('APP', __DIR__ . '/..');
define('CONFIG', APP . '/config/config.php');

set_time_limit(600);

require_once APP . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

try {

    $environment = Environment::memberByValue(getenv('ENVIRONMENT'));

    if ($environment->value() == Environment::DEVELOPMENT) {
        error_reporting(E_ERROR);
        ini_set('display_errors', 1);
    }

    (new App(CONFIG, $environment))->run();

} catch (Throwable $throwable) {

    header('Content-Type: application/json');
    http_response_code(503);

    die(Json::fromArray([
        'message' => $throwable->getMessage()
    ]));

}
