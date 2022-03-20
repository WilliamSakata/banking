<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\ImmutableCapabilities;
use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObject;
use LogicException;

final class Amount implements ValueObject
{
    use ImmutableCapabilities;

    public const ZERO = 0.0;

    /**
     * @param float $value
     * @param Currency $currency
     */
    public function __construct(private float $value, private Currency $currency)
    {
    }

    /**
     * @param Amount $amount
     * @return Amount
     */
    public function add(Amount $amount): Amount
    {
        $this->validateCurrency($amount);
        return new Amount($this->value + $amount->getValue(), $amount->getCurrency());
    }

    /**
     * @param Amount $amount
     * @return Amount
     */
    public function sub(Amount $amount): Amount
    {
        $this->validateCurrency($amount);
        return new Amount($this->value - $amount->getValue(), $amount->getCurrency());
    }

    /**
     * @param Amount $amount
     */
    private function validateCurrency(Amount $amount): void
    {
        if ($amount->getCurrency()->getValue() !== $this->currency->getValue()) {
            throw new LogicException('Currencies cannot be different');
        }
    }

    /**
     * @return bool
     */
    public function isZero(): bool
    {
        return $this->value === self::ZERO;
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->getValue(),
            'currency' => $this->getCurrency()->getValue()
        ];
    }
}
