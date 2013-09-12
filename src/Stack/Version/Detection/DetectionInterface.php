<?php
namespace Stack\Version\Detection;

use Symfony\Component\HttpFoundation\Request;

interface DetectionInterface
{
   /**
     * Detect the version based on the request.
     *
     * @param Request $request
     * @return string|null
     */
    public function detect(Request $request);
}
