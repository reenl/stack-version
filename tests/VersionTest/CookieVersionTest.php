<?php
namespace VersionTest;

use Stack\Version\CookieVersion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class CookieVersionTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        require_once __DIR__.'/test-kernels.php';
        $this->app = new CookieVersion(new KernelV1());
        $this->app->add(new KernelV2());
    }

    public function testCookieInResponse()
    {
        $request = new Request();

        $response = $this->app->handle($request);

        $this->assertEquals('v1', $response->getContent());

        $cookies = $response->headers->getCookies(ResponseHeaderBag::COOKIES_FLAT);
        $this->assertCount(1, $cookies);
        $this->assertEquals('1.0', $cookies[0]->getValue());
    }

    public function testForcedVersion()
    {
        $request = new Request();
        $request->cookies->add(array('version' => '2.0'));

        $response = $this->app->handle($request);

        $this->assertEquals('v2', $response->getContent());

        $cookies = $response->headers->getCookies(ResponseHeaderBag::COOKIES_FLAT);
        $this->assertCount(1, $cookies);
        $this->assertEquals('2.0', $cookies[0]->getValue());
    }
}
