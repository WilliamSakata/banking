<?php

namespace Banking\Account\Model\errors;

use LogicException;

final class InvalidBalance extends LogicException
{
    private const MESSAGE = 'Invalid amount for balance';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
