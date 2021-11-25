<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use LogicException;

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
            throw new LogicException(sprintf('Invalid currency %s', $value));
        }

        $this->value = strtoupper($value);
    }
}
