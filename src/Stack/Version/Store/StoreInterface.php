<?php
namespace Stack\Version\Store;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface StoreInterface
{
    /**
     * Adds the version to the response given by the application that matches
     * $version.
     *
     * A new instance of Response can be returned.
     *
     * @param Request $request
     * @param Response $response
     * @param string $version
     * @return Response
     */
    public function store(Request $request, Response $response, $version);

    /**
     * Fetches the stored version.
     *
     * @param Request $request
     * @return string|null
     */
    public function getStoredVersion(Request $request);
}
