<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests\Entity;

use Jgut\Pusher\Entity\DeviceEntity;

/**
 * Class DeviceEntityTest
 */
class DeviceEntityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DeviceEntity
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->entity = new DeviceEntity;
    }

    public function testUuid()
    {
        self::assertNull($this->entity->getUuid());
    }

    public function testToken()
    {
        $this->entity->setToken('aaa111bbb222ccc333');
        self::assertEquals('aaa111bbb222ccc333', $this->entity->getToken());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadToken()
    {
        $this->entity->setToken('@');
    }

    public function testPlatform()
    {
        $this->entity->setPlatform(DeviceEntity::PLATFORM_ANDROID);
        self::assertEquals(DeviceEntity::PLATFORM_ANDROID, $this->entity->getPlatform());

        $this->entity->setPlatform(DeviceEntity::PLATFORM_IOS);
        self::assertEquals(DeviceEntity::PLATFORM_IOS, $this->entity->getPlatform());

        $this->entity->setPlatform('android');
        self::assertEquals(DeviceEntity::PLATFORM_ANDROID, $this->entity->getPlatform());

        $this->entity->setPlatform('ios');
        self::assertEquals(DeviceEntity::PLATFORM_IOS, $this->entity->getPlatform());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadPlatform()
    {
        $this->entity->setPlatform('windows');
    }

    public function testSerialize()
    {
        $this->entity->setToken('aaa111bbb222ccc333');
        $this->entity->setPlatform(DeviceEntity::PLATFORM_ANDROID);

        $serializable = $this->entity->jsonSerialize();

        self::assertTrue(array_key_exists('uuid', $serializable));
        self::assertTrue(array_key_exists('token', $serializable));
        self::assertEquals('aaa111bbb222ccc333', $serializable['token']);
        self::assertTrue(array_key_exists('platform', $serializable));
        self::assertEquals('android', $serializable['platform']);
    }
}
