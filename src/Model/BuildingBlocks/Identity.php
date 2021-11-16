<?php

namespace Banking\Account\Model\BuildingBlocks;

use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObject;

interface Identity extends ValueObject
{
    /**
     * @return mixed
     */
    public function getValue(): mixed;
}