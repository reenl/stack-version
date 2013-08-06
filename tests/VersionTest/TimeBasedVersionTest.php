<?php
namespace VersionTest;

use Stack\Version\TimeBasedVersion;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class TimeBasedVersionTest extends \PHPUnit_Framework_TestCase
{
    protected $app;

    public function setUp()
    {
        require_once __DIR__.'/test-kernels.php';
        $this->app = new TimeBasedVersion(new KernelV1());
        $this->app->add(new KernelV2());
        $this->app->setRouter(function($time) {
            if ($time === 1337) {
                return '2.0';
            }
            return '1.0';
        });
    }

    /**
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Router must be a callable or null.
     */
    public function testInvalidRouter()
    {
        $this->app->setRouter('doesNotExist');
    }

    public function testDefaultVersionWithoutRouter()
    {
        $this->app->setRouter(null);
        $request = new Request();

        $response = $this->app->handle($request);
        $this->assertEquals('v1', $response->getContent());
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

    public function testStickyVersion()
    {
        $this->app->setSticky(true);

        $request = new Request();

        $response1 = $this->app->handle($request);
        $this->assertEquals('v1', $response1->getContent());

        $cookies = $response1->headers->getCookies(ResponseHeaderBag::COOKIES_FLAT);
        $this->assertEquals(1, count($cookies));

        $request->cookies->add(array(
            'version' => $cookies[0]->getValue()
        ));
        $request->server->set('REQUEST_TIME', 1337);

        $response2 = $this->app->handle($request);
        $this->assertEquals('v1', $response2->getContent());
    }
}
