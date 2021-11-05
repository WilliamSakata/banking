<?php

namespace Banking\Account\Model\ValueObject;

use LogicException;

final class Balance implements ValueObject
{
    public function __construct(private float $amount)
    {
        if ($amount < 0) {
            throw new LogicException('Invalid amount for balance');
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
