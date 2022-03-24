<?php

namespace Banking\Account\Model\BuildingBlocks\ValueObject;

trait SingleValueObjectCapabilities
{
    use ImmutableCapabilities;

    /**
     * @var mixed
     */
    protected mixed $value;

    /**
     * @return mixed
     */
    public function __invoke(): mixed
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->value;
    }

    /**
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }
}
