<?php

namespace Banking\Account\Model\BuildingBlocks;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use LogicException;

class Version implements SingleValueObject
{
    use SingleValueObjectCapabilities;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new LogicException('Version number cannot be less than 0');
        }

        $this->value = $value;
    }

    public function first(): Version
    {
        return new Version(1);
    }

    /**
     * @return Version
     */
    public function next(): Version
    {
        return static($this->value + 1);
    }
}