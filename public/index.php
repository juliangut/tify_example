<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

require __DIR__ . '/../vendor/autoload.php';

use Jgut\Pusher\App;
use Jgut\Pusher\Controller\DeviceController;
use Jgut\Pusher\Controller\NotificationController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr7Middlewares\Middleware\Cors;
use Psr7Middlewares\Middleware\TrailingSlash;

$app = new App();

$app->get('/', function (ServerRequestInterface $request, ResponseInterface $response) {
    $uri = (string) $request->getUri();
    $routes = [
        'device_register' => [
            'description' => 'Register device for notifications',
            'method' => 'POST',
            'uri' => $uri . '/register',
            'parameters' => [
                'token' => 'Device token as provided by push notification service (APNS or GCM)',
                'platform' => 'android or ios',
            ],
        ],
        'device_update' => [
            'description' => 'Update registered device token',
            'method' => 'PUT',
            'uri' => $uri . '/register',
            'parameters' => [
                'token' => 'Device token as provided by push notification service (APNS or GCM)',
                'previous_token' => 'Previously registered device token',
            ],
        ],
        'notification_send' => [
            'description' => 'Send message to devices',
            'method' => 'POST',
            'uri' => $uri . '/send',
            'parameters' => [
                'title' => 'Message title',
                'body' => 'Message content body',
                'platform' => 'Filter platform to send message (optional)',
                'data' => 'Key/value array of additional data to be appended to message (optional)',
            ],
        ],
    ];

    $response->getBody()->write(json_encode($routes));

    return $response;
});

$app->post('/register', [DeviceController::class, 'save']);
$app->put('/register', [DeviceController::class, 'update']);
$app->post('/send', [NotificationController::class, 'send']);

$app->add(function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
    $request = $request->withHeader('Accept', 'application/json');
    $response = $response->withHeader('Content-Type', 'application/json; charset=utf-8');

    return $next($request, $response);
});
$app->add(Cors::class);
$app->add(TrailingSlash::class);

$app->run();
