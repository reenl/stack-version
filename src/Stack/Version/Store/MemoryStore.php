<?php
namespace Stack\Version\Store;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MemoryStore implements StoreInterface
{
    protected $version;
    
    public function getStoredVersion(Request $request)
    {
        return $this->version;
    }

    public function store(Request $request, Response $response, $version)
    {
        $this->version = $version;
    }
}