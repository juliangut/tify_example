<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Repository;

use Doctrine\ORM\EntityRepository;
use Jgut\Pusher\Entity\DeviceEntity;

/**
 * Class DeviceRepository
 */
class DeviceRepository extends EntityRepository
{
    /**
     * @param DeviceEntity $device
     */
    public function saveDevice(DeviceEntity $device)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($device);
        $entityManager->flush();
    }
}
