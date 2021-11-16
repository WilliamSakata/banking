<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObjectCapabilities;
use LogicException;

final class Balance implements ValueObject
{
    use ValueObjectCapabilities;

    /**
     * @param float $amount
     */
    public function __construct(private float $amount)
    {
        if ($amount < 0) {
            throw new LogicException('Invalid amount for balance');
        }
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
