<?php
namespace Stack\Version;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class HttpKernelVersion implements HttpKernelVersionInterface
{
    /**
     *
     * @var Symfony\Component\HttpKernel\HttpKernelInterface
     */
    protected $app;

    /**
     *
     * @var string
     */
    protected $version;

    /**
     *
     * @param \Stack\Version\HttpKernelInterface $app
     * @param string $version
     */
    public function __construct(HttpKernelInterface $app, $version)
    {
        $this->app = $app;
        $this->version = $version;
    }

    /**
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     *
     * @param \Stack\Version\HttpKernelVersionInterface $app
     * @return mixed
     * -1 if the first version is lower than the second,
     * 0 if they are equal, and
     * 1 if the second is lower.
     * false if failed.
     */
    public function compareVersion(HttpKernelVersionInterface $app)
    {
        return version_compare($this->extractVersion(), $app->extractVersion());
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return $this->app->handle($request, $type, $catch);
    }
}
