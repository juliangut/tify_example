<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests\Entity;

use Jgut\Pusher\Controller\DeviceController;
use Jgut\Pusher\Entity\DeviceEntity;
use Jgut\Pusher\Repository\DeviceRepository;
use Psr\Http\Message\ServerRequestInterface;
use Ramsey\Uuid\Uuid;
use Slim\Http\Response;

/**
 * Class DeviceControllerTest
 */
class DeviceControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $repository = $this->getMock(DeviceRepository::class, [], [], '', null);
        $repository->expects(self::once())->method('findOneBy')->will(self::returnValue(null));
        $repository->expects(self::once())->method('saveDevice');

        $parameters = [
            'token' => 'aaaa',
            'platform' => 'android',
        ];

        $request = $this->getMock(ServerRequestInterface::class, [], [], '', null);
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        $controller = new DeviceController($repository);

        /** @var Response $response */
        $response = $controller->save($request, new Response());

        self::assertEquals(
            json_encode(['uuid' => null, 'token' => 'aaaa', 'platform' => 'android']),
            (string) $response->getBody()
        );
    }

    public function testNotSave()
    {
        $uuid = Uuid::uuid4();
        $device = (new DeviceEntity())
            ->setUuid($uuid)
            ->setToken('bbbb')
            ->setPlatform('ios');

        $repository = $this->getMock(DeviceRepository::class, [], [], '', null);
        $repository->expects(self::once())->method('findOneBy')->will(self::returnValue($device));

        $parameters = [
            'token' => 'bbbb',
            'platform' => 'ios',
        ];

        $request = $this->getMock(ServerRequestInterface::class, [], [], '', null);
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        $controller = new DeviceController($repository);

        /** @var Response $response */
        $response = $controller->save($request, new Response());

        self::assertEquals(
            json_encode(['uuid' => (string) $uuid, 'token' => 'bbbb', 'platform' => 'ios']),
            (string) $response->getBody()
        );
    }

    public function testUpdate()
    {
        $uuid = Uuid::uuid4();
        $device = (new DeviceEntity())
            ->setUuid($uuid)
            ->setToken('bbbb')
            ->setPlatform('ios');

        $repository = $this->getMock(DeviceRepository::class, [], [], '', null);
        $repository->expects(self::once())->method('findOneBy')->will(self::returnValue($device));
        $repository->expects(self::once())->method('saveDevice');

        $parameters = [
            'token' => 'cccc',
            'previous_token' => 'bbbb',
        ];

        $request = $this->getMock(ServerRequestInterface::class, [], [], '', null);
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        $controller = new DeviceController($repository);

        /** @var Response $response */
        $response = $controller->update($request, new Response());

        self::assertEquals(
            json_encode(['uuid' => (string) $uuid, 'token' => 'cccc', 'platform' => 'ios']),
            (string) $response->getBody()
        );
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testNoDeviceUpdate()
    {
        $repository = $this->getMock(DeviceRepository::class, [], [], '', null);
        $repository->expects(self::once())->method('findOneBy')->will(self::returnValue(null));

        $parameters = [
            'token' => 'cccc',
            'previous_token' => 'bbbb',
        ];

        $request = $this->getMock(ServerRequestInterface::class, [], [], '', null);
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        $controller = new DeviceController($repository);

        $controller->update($request, new Response());
    }
}
