<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Jgut\Pusher\Entity\DeviceEntity;
use Jgut\Pusher\Repository\DeviceRepository;

/**
 * Class DeviceRepositoryTest
 */
class DeviceRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DeviceRepository
     */
    protected $repository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $entityManager->expects(self::once())->method('persist');
        $entityManager->expects(self::once())->method('flush');

        $this->repository = new DeviceRepository($entityManager, new ClassMetadata('DeviceEntity'));
    }

    public function testApp()
    {
        $this->repository->saveDevice(new DeviceEntity);
    }
}
