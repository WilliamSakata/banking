<?php

namespace Banking\Account\Model\BuildingBlocks\ValueObject;

use RuntimeException;

trait ImmutableCapabilities
{
    /**
     * @param $key
     */
    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            throw new RuntimeException(sprintf('Property %s invalid for %s', $key, get_called_class()));
        }
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        throw new RuntimeException(
            sprintf('Type %s must be immutable and cannot change the value of %s', get_called_class(), $key)
        );
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        throw new RuntimeException(sprintf('The %s value cannot be disabled', $key));
    }

    /**
     * @param Immutable $obj
     * @return bool
     */
    public function equals(Immutable $obj): bool
    {
        return $obj == $this;
    }
}