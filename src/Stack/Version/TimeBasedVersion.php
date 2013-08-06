<?php
namespace Stack\Version;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Route to a version based on time. With sticky enabled, the version will be
 * stored within a cookie, to prevent a user from seeing multiple versions of
 * the website within a session.
 */
class TimeBasedVersion extends CookieVersion
{
    /**
     * A callable that accepts a time parameter to return a version.
     *
     * @var callable
     */
    protected $router = null;

    /**
     * Should the current version be stored in a cookie.
     *
     * @var boolean
     */
    protected $sticky = false;

    /**
     * A callable that accepts the time parameter.
     *
     * @param callable $router
     * @return \Stack\Version\TimeBasedVersion
     */
    public function setRouter($router)
    {
        if (!is_callable($router) && $router !== null) {
            throw new \InvalidArgumentException('Router must be a callable or null.');
        }
        $this->router = $router;
        return $this;
    }

    /**
     * Should the current version be stored in a cookie.
     *
     * @param boolean $sticky
     * @return \Stack\Version\TimeBasedVersion
     */
    public function setSticky($sticky)
    {
        $this->sticky = (bool)$sticky;
        return $this;
    }

    /**
     * Should the current version be stored in a cookie.
     *
     * @return boolean
     */
    public function getSticky()
    {
        return $this->sticky;
    }

    /**
     * Adds the version cookie to the response if sticky is true.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param string $version
     * @return null
     */
    public function addVersionToResponse(Request $request, Response $response, $version)
    {
        if (!$this->getSticky()) {
            return;
        }

        parent::addVersionToResponse($request, $response, $version);
    }

    /**
     * Extract the current version. If a cookie is set, use the cookie else
     * check the router.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    public function extractVersion(Request $request)
    {
        $version = parent::extractVersion($request);
        if ($version !== null) {
            // Version exists within cookie, prevent version swap.
            return $version;
        }

        if ($this->router === null) {
            // No router, use default version.
            return null;
        }

        $time = $request->server->get('REQUEST_TIME');

        // Ask the router for a version.
        return call_user_func($this->router, $time);
    }
}
