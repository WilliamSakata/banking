<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObject;
use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObjectCapabilities;
use LogicException;

final class Currency implements ValueObject
{
    use ValueObjectCapabilities;

    private const SIZE = 3;

    /**
     * @param string $value
     */
    public function __construct(private string $value)
    {
        if (strlen($value) !== self::SIZE) {
            throw new LogicException(sprintf('Invalid currency %s', $value));
        }
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return mb_strtoupper($this->value);
    }
}
