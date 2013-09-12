<?php
namespace Stack\Version\Detection;

use Symfony\Component\HttpFoundation\Request;

class FixedVersionDetection implements DetectionInterface
{
    protected $version;

    public function __construct($version)
    {
        $this->version = $version;
    }

    public function detect(Request $request)
    {
        return $this->version;
    }
}