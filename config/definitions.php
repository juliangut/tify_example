<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

use Interop\Container\ContainerInterface;
use Jgut\Pusher\Entity\DeviceEntity;
use Jgut\Pusher\Repository\DeviceRepository;
use Jgut\Slim\Doctrine\EntityManagerBuilder;
use Jgut\Tify\Adapter\Gcm\GcmAdapter;
use Jgut\Tify\Adapter\Apns\ApnsAdapter;
use Jgut\Tify\Service;
use Psr7Middlewares\Middleware\Cors;
use Ramsey\Uuid\Doctrine\UuidType;

return [
    'settings.displayErrorDetails' => true,

    'entityManager' => function () {
        $entityManagerConfig = [
            'connection' => [
                //'driver' => 'pdo_sqlite',
                'pdo' => new PDO(sprintf('sqlite:%s/pusher.sqlite', __DIR__)),
            ],
            'annotation_paths' => [__DIR__ . '/../src/Entity'],
            'custom_types' => [
                'uuid' => UuidType::class,
            ],
        ];

        return EntityManagerBuilder::build($entityManagerConfig);
    },

    DeviceRepository::class => function (ContainerInterface $container) {
        return $container->get('entityManager')->getRepository(DeviceEntity::class);
    },

    Service::class => function () {
        $service = new Service;

        if (file_exists(__DIR__ . '/api.key')) {
            $service->addAdapter(
                new GcmAdapter(['api_key' => rtrim(file_get_contents(__DIR__ . '/api.key'), "\n")])
            );
        }
        if (file_exists(__DIR__ . '/certificate.pem')) {
            $service->addAdapter(new ApnsAdapter(['certificate' => __DIR__ . '/certificate.pem'], true));
        }

        if (count($service->getAdapters()) === 0) {
            throw new \RuntimeException(
                'No adapters have been defined, create "api.key" or "certificate.pem" files'
            );
        }

        return $service;
    },

    Cors::class => function () {
        return (new Cors())
            ->allowedOrigins([
                '*' => true,
            ])
            ->allowedMethods([
                'POST' => true,
                'PUT' => true,
            ]);
    }
];
