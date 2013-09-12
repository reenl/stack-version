<?php
namespace Stack\Version\Store;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

class CookieStore implements StoreInterface
{
    /**
     *
     * @var string
     */
    protected $cookieName;

    /**
     *
     * @var array
     */
    protected $cookieParams;

    /**
     *
     * @param string $cookieName
     * @param array $cookieParams
     */
    public function __construct($cookieName = 'version', $cookieParams = array())
    {
        $this->cookieName = $cookieName;
        $this->cookieParams = $cookieParams;
    }

    /**
     * {@inheritdoc}
     */
    public function getStoredVersion(Request $request)
    {
        return $request->cookies->get($this->cookieName);
    }

    /**
     * {@inheritdoc}
     */
    public function store(Request $request, Response $response, $version)
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
