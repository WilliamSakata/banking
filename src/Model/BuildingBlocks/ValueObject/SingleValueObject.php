<?php

namespace Banking\Account\Model\BuildingBlocks\ValueObject;

interface SingleValueObject extends Immutable
{
    /**
     * @return mixed
     */
    public function __invoke(): mixed;

    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return mixed
     */
    public function getValue(): mixed;
}