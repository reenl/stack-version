<?php
namespace Stack\Version;

use Stack\Version\Detection\DetectionInterface;
use Stack\Version\Store\StoreInterface;
use Stack\Version\Switcher\SwitcherInterface;

interface AdapterInterface extends DetectionInterface, StoreInterface, SwitcherInterface
{
}
