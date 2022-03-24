<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use Banking\Account\Model\errors\InvalidCurrency;

final class Currency implements SingleValueObject
{
    use SingleValueObjectCapabilities;

    private const SIZE = 3;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (strlen($value) !== self::SIZE) {
            throw new InvalidCurrency($value);
        }

        $this->value = strtoupper($value);
    }
}
