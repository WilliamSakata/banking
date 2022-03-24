<?php

namespace Banking\Account\Model\errors;

use LogicException;

final class InvalidCurrency extends LogicException
{
    private const MESSAGE = 'Invalid currency %s';

    public function __construct(string $currency)
    {
        $message = sprintf(self::MESSAGE, $currency);

        parent::__construct($message);
    }
}
