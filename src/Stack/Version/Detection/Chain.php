<?php
namespace Stack\Version\Detection;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class Chain implements DetectionInterface
{
    /**
     *
     * @var DetectionInterface[]
     */
    protected $chain = array();

    /**
     *
     * @param DetectionInterface[] $chain
     * @throws InvalidArgumentException
     */
    public function __construct(array $chain)
    {
        foreach ($chain as $item) {
            if (!$item instanceof DetectionInterface) {
                throw new InvalidArgumentException(
                    'The chain should only contain instances of DetectionInterface.'
                );
            }
            $this->chain[] = $item;
        }
    }

    /**
     *
     * @param Request $request
     * @return null|string
     */
    public function detect(Request $request)
    {
        foreach ($this->chain as $detection) {
            $version = $detection->detect($request);
            if ($version !== null) {
                return $version;
            }
        }
        return null;
    }
}
