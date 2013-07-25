<?php
namespace Stack\Version;

use Symfony\Component\HttpKernel\HttpKernelInterface;

interface HttpKernelVersionInterface extends HttpKernelInterface
{
    public function getVersion();
}