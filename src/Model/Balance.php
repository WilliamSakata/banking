<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use LogicException;

final class Balance implements SingleValueObject
{
    use SingleValueObjectCapabilities;

    /**
     * @param float $amount
     */
    public function __construct(float $amount)
    {
        if ($amount < 0) {
            throw new LogicException('Invalid amount for balance');
        }

        $this->value = $amount;
    }
}
