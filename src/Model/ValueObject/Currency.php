<?php

namespace Banking\Account\Model\ValueObject;

use LogicException;

final class Currency implements ValueObject
{
    private const SIZE = 3;

    public function __construct(private string $value)
    {
        if (strlen($value) !== self::SIZE) {
            throw new LogicException(sprintf('Invalid currency %s', $value));
        }
    }

    public function getValue(): string
    {
        return mb_strtoupper($this->value);
    }
}
