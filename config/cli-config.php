<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

// Ensure UTC default Time Zone
if (date_default_timezone_get() !== 'UTC') {
    date_default_timezone_set('UTC');
}

require __DIR__ . '/../vendor/autoload.php';

use Jgut\Pusher\App;
use Jgut\Slim\Doctrine\CLIApplicationBuilder;

$app = new App();

/* @var \DI\Container $container */
$container = $app->getContainer();

$application = CLIApplicationBuilder::build($container->get('entityManager'));

return $application->run();
