<?php
namespace Stack\Version\Detection;

use Symfony\Component\HttpFoundation\Request;

class HeaderDetection implements DetectionInterface
{
    protected $header;

    public function __construct($header)
    {
        $this->header = $header;
    }

    public function detect(Request $request)
    {
        return $request->headers->get($this->header);
    }
}