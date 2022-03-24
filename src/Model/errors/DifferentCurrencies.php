<?php

namespace Banking\Account\Model\errors;

use LogicException;

final class DifferentCurrencies extends LogicException
{
    private const MESSAGE = 'Currencies cannot be different';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
