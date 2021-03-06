<?php
namespace VersionTest;

use Stack\Version\VersionSwitchKernel;
use Stack\Version\Adapter;
use Stack\Version\Detection\TimeDetection;
use Symfony\Component\HttpFoundation\Request;

class TimeBasedVersionTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        require_once __DIR__.'/test-kernels.php';
        $adapter = new Adapter(new TimeDetection(function($time) {
            if ($time === 1337) {
                return '2.0';
            }
            return '1.0';
        }));

        $this->app = new VersionSwitchKernel(new KernelV1(), '1.0', array(
            '2.0' => new KernelV2()
        ), $adapter);
    }

    /**
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Router must be a callable.
     */
    public function testInvalidRouter()
    {
        new TimeDetection('doesNotExist');
    }

    public function testVersionSwitchedBasedOnTime()
    {
        $request = new Request();

        $response1 = $this->app->handle($request);
        $this->assertEquals('v1', $response1->getContent());

        $request->server->set('REQUEST_TIME', 1337);

        $response2 = $this->app->handle($request);
        $this->assertEquals('v2', $response2->getContent());
    }
}
