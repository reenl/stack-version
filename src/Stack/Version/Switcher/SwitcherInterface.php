<?php
namespace Stack\Version\Switcher;

use Symfony\Component\HttpFoundation\Request;

interface SwitcherInterface
{
    /**
     * If the current request can not be handled by the $version, this function
     * will return a response telling the consumer what to do.
     *
     * If null is returned the app with $version will handle the current
     * response.
     *
     * Some examples:
     * For API's it could be practical to return "unsupported version" and add
     * an url the the new $version.
     *
     * For websites this might return a redirect response to send the user to
     * the new version.
     *
     * @param Request $request
     * @param string $from The current version
     * @param string $to The new version
     * @return null|Response
     */
    public function createSwitchResponse(Request $request, $from, $to);
}