<?php

namespace Banking\Account\Model\BuildingBlocks;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;

trait IdentityCapabilities
{
    use SingleValueObjectCapabilities;

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}