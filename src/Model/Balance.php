<?php

namespace Banking\Account\Model;

use InvalidArgumentException;

class Balance
{
    private float $value;

    /**
     * @param float $value
     */
    public function __construct(float $value)
    {
        if(!$this->isValid($value))
        {
            throw new InvalidArgumentException('Invalid balance');
        }

        $this->value = $value;
    }

    /**
     * @param float $value
     * @return bool
     */
    private function isValid(float $value): bool
    {
        if($value < 0){
            return false;
        }

        return true;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}