<?php
/**
 * Push notification server example (http://github.com/juliangut/tify_example)
 *
 * @link https://github.com/juliangut/tify_example for the canonical source repository
 *
 * @license https://github.com/juliangut/tify_example/blob/master/LICENSE
 */

namespace Jgut\Pusher\Tests\Entity;

use Jgut\Pusher\Controller\AbstractController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractControllerTest
 */
class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractController
     */
    protected $controller;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->controller = $this->getMockForAbstractClass(AbstractController::class);
    }

    public function testParameters()
    {
        $reflection = new \ReflectionClass(get_class($this->controller));
        $method = $reflection->getMethod('resolveRequestParameters');
        $method->setAccessible(true);

        $parameters = [
            'param1' => 'value1',
        ];

        $request = $this->getMockBuilder(ServerRequestInterface::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue($parameters));

        self::assertEquals($parameters, $method->invokeArgs($this->controller, [$request, ['param1'], [], ['param1']]));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testBadParameters()
    {
        $reflection = new \ReflectionClass(get_class($this->controller));
        $method = $reflection->getMethod('resolveRequestParameters');
        $method->setAccessible(true);

        $request = $this->getMockBuilder(ServerRequestInterface::class)->disableOriginalConstructor()->getMock();
        $request->expects(self::once())->method('getParsedBody')->will(self::returnValue([]));

        $method->invokeArgs($this->controller, [$request, ['param1'], [], ['param1']]);
    }
}
