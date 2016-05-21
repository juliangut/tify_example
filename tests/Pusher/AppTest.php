<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests;

use Doctrine\ORM\EntityManager;
use Jgut\Pusher\App;

/**
 * Class AppTest
 */
class AppTest extends \PHPUnit_Framework_TestCase
{
    public function testApp()
    {
        $container = (new App())->getContainer();

        self::assertTrue($container->get('settings')['displayErrorDetails']);
        self::assertInstanceOf(EntityManager::class, $container->get('entityManager'));
    }
}
