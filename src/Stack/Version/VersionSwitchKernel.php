<?php
namespace Stack\Version;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class VersionSwitchKernel implements HttpKernelInterface
{
    /**
     *
     * @var HttpKernelVersionInterface[]
     */
    protected $apps = array();

    /**
     *
     * @var string
     */
    protected $defaultVersion;

    /**
     *
     * @var AdapterInterface
     */
    protected $adapter;

    public function __construct(HttpKernelInterface $app, $version, array $apps, AdapterInterface $adapter)
    {
        $this->defaultVersion = $version;
        $this->apps[$version] = $app;
        foreach ($apps as $v => $app) {
            $this->add($app, $v);
        }
        $this->adapter = $adapter;
    }

    /**
     *
     * @param HttpKernelInterface $app
     * @param string $version
     * @return \Stack\Version\VersionSwitch
     */
    protected function add(HttpKernelInterface $app, $version)
    {
        $this->apps[$version] = $app;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $type
     * @param boolean $catch
     * @return Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $current = $this->adapter->detect($request);
        if (!$this->hasVersion($current)) {
            // The version is undefined or does not exist anymore.
            $current = $this->defaultVersion;
        }

        $stored = $this->adapter->getStoredVersion($request);
        if ($stored !== null && $current !== $stored) {
            return $this->handleSwitch($stored, $current, $request, $type, $catch);
        }
        return $this->handleVersion($current, $request, $type, $catch);
    }

    /**
     * The version is switching.
     *
     * @param string $oldVersion
     * @param string $version
     * @param Request $request
     * @param int $type
     * @param boolean $catch
     * @return Response
     */
    protected function handleSwitch($oldVersion, $version, Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $response = $this->adapter->createSwitchResponse($request, $oldVersion, $version);
        if ($response === null) {
            return $this->handleVersion($version, $request, $type, $catch);
        }

        $this->adapter->store($request, $response, $version);
        return $response;
    }

    /**
     *
     * @param string $version
     * @param Request $request
     * @param int $type
     * @param boolean $catch
     * @return Response
     */
    protected function handleVersion($version, Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $response = $this->apps[$version]->handle($request, $type, $catch);
        $this->adapter->store($request, $response, $version);
        return $response;
    }

    /**
     * Does the class contain the version?
     *
     * @param string $version
     * @return boolean
     */
    protected function hasVersion($version)
    {
        return isset($this->apps[$version]);
    }
}
