<?php
namespace VersionTest;

use Stack\Version\VersionSwitchKernel;
use Stack\Version\Adapter;
use Stack\Version\Detection\HeaderDetection;
use Stack\Version\Store\MemoryStore;
use Symfony\Component\HttpFoundation\Request;

class VersionSwitchKernelTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        require_once __DIR__.'/test-kernels.php';
        $adapter = new Adapter(new HeaderDetection('X-Version'), new MemoryStore());

        $this->app = new VersionSwitchKernel(new KernelV1(), '1.0', array(
            '2.0' => new KernelV2()
        ), $adapter);
    }

    public function testSwitchIsHandledCorrectly()
    {
        $request = new Request();

        $response1 = $this->app->handle($request);
        $this->assertEquals('v1', $response1->getContent());

        $request->headers->set('X-Version', '2.0');

        $response2 = $this->app->handle($request);
        $this->assertEquals('v2', $response2->getContent());
    }
}
