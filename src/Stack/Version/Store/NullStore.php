<?php
namespace Stack\Version\Store;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NullStore implements StoreInterface
{
    public function getStoredVersion(Request $request)
    {
        return null;
    }

    public function store(Request $request, Response $response, $version)
    {
    }
}