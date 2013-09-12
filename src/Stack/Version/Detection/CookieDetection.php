<?php
namespace Stack\Version\Detection;

use Symfony\Component\HttpFoundation\Request;

class CookieDetection implements DetectionInterface
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
    public function __construct($cookieName = 'version')
    {
        $this->cookieName = $cookieName;
    }

    /**
     * {@inheritdoc}
     */
    public function detect(Request $request)
    {
        return $request->cookies->get($this->cookieName);
    }
}

