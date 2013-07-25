<?php
namespace Stack\Version;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class CookieVersion extends AbstractVersion
{
    protected $cookieName;
    protected $cookieParams;

    public function __construct(
            HttpKernelVersionInterface $app,
            $cookieName = 'version', $cookieParams = array())
    {
        parent::__construct($app);
        $this->cookieName = $cookieName;
        $this->cookieParams = $cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function extractVersion(Request $request)
    {
        return $request->cookies->get($this->cookieName);
    }

    /**
     * {@inheritdoc}
     */
    public function createVersionChangedResponse(Request $request, $version)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function addVersionToResponse(Request $request, Response $response, $version)
    {
        $params = array_merge(
            session_get_cookie_params(),
            $this->cookieParams
        );
        $cookie = new Cookie(
            $this->cookieName,
            $version,
            0 === $params['lifetime'] ? 0 : $request->server->get('REQUEST_TIME') + $params['lifetime'],
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
        $response->headers->setCookie($cookie);
    }
}
