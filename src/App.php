<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher;

use DI\Bridge\Slim\App as BaseApp;
use DI\ContainerBuilder;

/**
 * Class App
 */
class App extends BaseApp
{
    /**
     * {@inheritdoc}
     */
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions(__DIR__ . '/../config/definitions.php');
    }
}
