<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use Banking\Account\Model\errors\InvalidBalance;

final class Balance implements SingleValueObject
{
    use SingleValueObjectCapabilities;

    /**
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new InvalidBalance();
        }

        $this->value = $amount;
    }
}
