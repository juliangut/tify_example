<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Controller;

use Jgut\Pusher\Entity\DeviceEntity;
use Jgut\Pusher\Repository\DeviceRepository;
use Jgut\Tify\Message;
use Jgut\Tify\Notification;
use Jgut\Tify\Receiver\ApnsReceiver;
use Jgut\Tify\Receiver\GcmReceiver;
use Jgut\Tify\Service;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class NotificationController
 */
class NotificationController extends AbstractController
{
    /**
     * @var DeviceRepository
     */
    protected $repository;

    /**
     * @var Service
     */
    protected $tify;

    /**
     * @param DeviceRepository $repository
     * @param Service          $tify
     */
    public function __construct(DeviceRepository $repository, Service $tify)
    {
        $this->repository = $repository;
        $this->tify = $tify;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     *
     * @return ResponseInterface
     */
    public function send(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parameters = $this->resolveRequestParameters(
            $request,
            ['title', 'body', 'platform', 'data'],
            [],
            ['title', 'body']
        );

        if (array_key_exists('platform', $parameters)) {
            $devices = $this->repository->findBy(['platform' => $parameters['platform']]);
        } else {
            $devices = $this->repository->findAll();
        }

        /* @var DeviceEntity[] $devices */
        if (count($devices)) {
            /* @var \Jgut\Tify\Receiver\AbstractReceiver[] $receivers */
            $receivers = array_map(
                function (DeviceEntity $device) {
                    switch ($device->getPlatform()) {
                        case DeviceEntity::PLATFORM_ANDROID:
                            return new GcmReceiver($device->getToken());

                        case DeviceEntity::PLATFORM_IOS:
                            return new ApnsReceiver($device->getToken());
                    }

                    return;
                },
                $devices
            );

            $message = new Message([
                'title' => $parameters['title'],
                'body' => $parameters['body'],
            ]);

            if (array_key_exists('data', $parameters)) {
                $message->setPayloadData($parameters['data']);
            }

            $this->tify->addNotification(new Notification($message, array_filter($receivers)));
        }

        $response->getBody()->write(json_encode($this->tify->push()));

        return $response;
    }
}
