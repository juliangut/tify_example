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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class DeviceController
 */
class DeviceController extends AbstractController
{
    /**
     * @var DeviceRepository
     */
    protected $repository;

    /**
     * @param DeviceRepository $repository
     */
    public function __construct(DeviceRepository $repository)
    {
        $this->repository = $repository;
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
    public function save(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parameters = $this->resolveRequestParameters(
            $request,
            ['token', 'platform'],
            [],
            ['token', 'platform']
        );

        /* @var DeviceEntity $device */
        $device = $this->repository->findOneBy(['token' => $parameters['token']]);
        if (!$device) {
            $device = new DeviceEntity();
            $device->setToken($parameters['token']);
            $device->setPlatform($parameters['platform']);

            $this->repository->saveDevice($device);
        }

        $response->getBody()->write(json_encode($device));

        return $response;
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
    public function update(ServerRequestInterface $request, ResponseInterface $response)
    {
        $parameters = $this->resolveRequestParameters(
            $request,
            ['token', 'previous_token'],
            [],
            ['token', 'previous_token']
        );

        /* @var DeviceEntity $device */
        $device = $this->repository->findOneBy(['token' => $parameters['previous_token']]);
        if (!$device) {
            throw new \RuntimeException('Impossible to find device with previous token');
        }

        $device->setToken($parameters['token']);

        $this->repository->saveDevice($device);

        $response->getBody()->write(json_encode($device));

        return $response;
    }
}
