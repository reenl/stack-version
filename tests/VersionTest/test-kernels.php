<?php
namespace VersionTest;

use Stack\Version\HttpKernelVersionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class KernelV1 implements HttpKernelVersionInterface
{
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return new Response('v1');
    }

    public function getVersion()
    {
        return '1.0';
    }
}

class KernelV2 implements HttpKernelVersionInterface
{
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        return new Response('v2');
    }

    public function getVersion()
    {
        return '2.0';
    }
}