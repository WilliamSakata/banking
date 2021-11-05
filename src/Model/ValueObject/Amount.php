<?php

namespace Banking\Account\Model\ValueObject;

use LogicException;

final class Amount implements ValueObject
{
    public const ZERO = 0.0;

    public function __construct(private float $value, private Currency $currency)
    {
    }

    public function add(Amount $amount): Amount
    {
        $this->validateCurrency($amount);
        return new Amount($this->value + $amount->getValue(), $amount->getCurrency());
    }

    public function sub(Amount $amount): Amount
    {
        $this->validateCurrency($amount);
        return new Amount($this->value - $amount->getValue(), $amount->getCurrency());
    }

    private function validateCurrency(Amount $amount): void
    {
        if ($amount->getCurrency()->getValue() !== $this->currency->getValue()) {
            throw new LogicException('Currencies cannot be different');
        }
    }

    public function isZero(): bool
    {
        return $this->value === self::ZERO;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}
