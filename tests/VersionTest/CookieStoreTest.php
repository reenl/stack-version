<?php
namespace VersionTest;

use Stack\Version\VersionSwitchKernel;
use Stack\Version\Adapter;
use Stack\Version\Detection\HeaderDetection;
use Stack\Version\Store\CookieStore;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieStoreTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        require_once __DIR__.'/test-kernels.php';

        $adapter = new Adapter(new HeaderDetection('X-Version'), new CookieStore());
        $this->app = new VersionSwitchKernel(new KernelV1(), '1.0', array(
            '2.0' => new KernelV2()
        ), $adapter);
    }

    public function testCookieInResponse()
    {
        $request = new Request();

        $response = $this->app->handle($request);

        $this->assertEquals('v1', $response->getContent());

        $cookies = $response->headers->getCookies(ResponseHeaderBag::COOKIES_FLAT);
        $this->assertEquals(1, count($cookies));
        $this->assertEquals('1.0', $cookies[0]->getValue());
    }
}
