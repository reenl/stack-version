<?php
namespace Stack\Version;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

abstract class AbstractVersion implements HttpKernelInterface
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
    protected $default;

    public function __construct(HttpKernelVersionInterface $app)
    {
        $this->default = $app->getVersion();
        $this->apps[$this->default] = $app;
    }

    /**
     *
     * @param \Stack\Version\HttpKernelVersionInterface $app
     * @return \Stack\Version\VersionSwitch
     */
    public function add(HttpKernelVersionInterface $app)
    {
        $this->apps[$app->getVersion()] = $app;
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
        $version = $this->extractVersion($request);
        if ($version === null || !isset($this->apps[$version])) {
            $version = $this->default;

            $response = $this->createVersionChangedResponse($request, $version);
            if ($response !== null) {
                return $response;
            }
        }

        $response = $this->apps[$version]->handle($request, $type, $catch);
        $this->addVersionToResponse($request, $response, $version);
        return $response;
    }

    /**
     * Extract the version from the request.
     *
     * @param Request $request
     * @return string
     */
    abstract public function extractVersion(Request $request);

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
     * @param string $version
     * @return null|Response
     */
    abstract public function createVersionChangedResponse(Request $request, $version);

    /**
     * Adds the version to the response given by the application that matches
     * $version.
     *
     * A new instance of Response can be returned.
     *
     * @param Request $request
     * @param Response $response
     * @param string $version
     * @return null
     */
    abstract public function addVersionToResponse(Request $request, Response $response, $version);
}
