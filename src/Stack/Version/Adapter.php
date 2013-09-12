<?php
namespace Stack\Version;

use Stack\Version\Detection\DetectionInterface;
use Stack\Version\Store\NullStore;
use Stack\Version\Store\StoreInterface;
use Stack\Version\Switcher\NullSwitcher;
use Stack\Version\Switcher\SwitcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Adapter implements AdapterInterface
{
    /**
     *
     * @var DetectionInterface
     */
    protected $detection;

    /**
     *
     * @var StoreInterface
     */
    protected $store;

    /**
     *
     * @var SwitcherInterface
     */
    protected $switcher;

    /**
     *
     * @param DetectionInterface $detection
     * @param StoreInterface $store
     * @param SwitcherInterface $switcher
     */
    public function __construct(
        DetectionInterface $detection,
        StoreInterface $store = null,
        SwitcherInterface $switcher = null)
    {
        $this->detection = $detection;
        $this->store = $store ?: new NullStore();
        $this->switcher = $switcher ?: new NullSwitcher();
    }

    public function detect(Request $request)
    {
        return $this->detection->detect($request);
    }

    public function store(Request $request, Response $response, $version)
    {
        return $this->store->store($request, $response, $version);
    }

    public function getStoredVersion(Request $request)
    {
        return $this->store->getStoredVersion($request);
    }

    public function createSwitchResponse(Request $request, $from, $to)
    {
        return $this->switcher->createSwitchResponse($request, $from, $to);
    }
}
