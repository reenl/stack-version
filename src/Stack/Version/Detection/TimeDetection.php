<?php
namespace Stack\Version\Detection;

use Symfony\Component\HttpFoundation\Request;

class TimeDetection implements DetectionInterface
{
    /**
     * A callable that accepts a time parameter to return a version.
     *
     * @var callable
     */
    protected $router = null;

    /**
     * A callable that accepts the timestamp (int) parameter.
     *
     * @param callable $router
     */
    public function __construct($router)
    {
        if (!is_callable($router)) {
            throw new \InvalidArgumentException('Router must be a callable.');
        }
        $this->router = $router;
    }

    /**
     * Extract the current version. If a cookie is set, use the cookie else
     * check the router.
     *
     * @param Request $request
     * @return string
     */
    public function detect(Request $request)
    {
        $time = $request->server->get('REQUEST_TIME');

        // Ask the router for a version.
        return call_user_func($this->router, $time);
    }
}
