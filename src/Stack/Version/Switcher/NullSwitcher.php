<?php
namespace Stack\Version\Switcher;

use Symfony\Component\HttpFoundation\Request;

class NullSwitcher implements SwitcherInterface
{
    public function createSwitchResponse(Request $request, $from, $to)
    {
        return null;
    }
}
