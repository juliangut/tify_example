<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests\Entity;

use Jgut\Pusher\Controller\NotificationController;
use Jgut\Pusher\Entity\DeviceEntity;
use Jgut\Pusher\Repository\DeviceRepository;
use Jgut\Tify\Service;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

/**
 * Class NotificationControllerTest
 */
class NotificationControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testNoDevices()
    {
        $repository = $this->getMockBuilder(DeviceRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects(self::once())->method('findAll')->will(self::returnValue(null));

        $service = $this->getMockBuilder(Service::class)->disableOriginalConstructor()->getMock();
        $service->expects(self::once())->method('push')->will(self::returnValue([]));

        $controller = new NotificationController($repository, $service);

        $parameters = [
            'title' => 'Title',
            'body' => 'Body',
            'data' => [
                'param1' => 'value1',
            ],
        ];
        $request = $this->getMockBuilder(ServerRequestInterface::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        /** @var Response $response */
        $response = $controller->send($request, new Response());

        self::assertEquals(
            json_encode([]),
            (string) $response->getBody()
        );
    }

    public function testSend()
    {
        $devices = [
            (new DeviceEntity())->setToken('aaaa')->setPlatform('android'),
            (new DeviceEntity())
                ->setToken('0000000000000000000000000000000000000000000000000000000000000000')
                ->setPlatform('ios'),
            new DeviceEntity(),
        ];

        $repository = $this->getMockBuilder(DeviceRepository::class)->disableOriginalConstructor()->getMock();
        $repository->expects(self::once())->method('findBy')->will(self::returnValue($devices));

        $service = $this->getMockBuilder(Service::class)->disableOriginalConstructor()->getMock();
        $service->expects(self::once())->method('push')->will(self::returnValue([]));

        $controller = new NotificationController($repository, $service);

        $parameters = [
            'title' => 'Title',
            'body' => 'Body',
            'platform' => 'android',
            'data' => [
                'param1' => 'value1',
            ],
        ];
        $request = $this->getMockBuilder(ServerRequestInterface::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        /** @var Response $response */
        $response = $controller->send($request, new Response());

        self::assertEquals(
            json_encode([]),
            (string) $response->getBody()
        );
    }
}
