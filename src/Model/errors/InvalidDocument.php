<?php

namespace Banking\Account\Model\errors;

use InvalidArgumentException;

final class InvalidDocument extends InvalidArgumentException
{
    private const MESSAGE = 'Invalid CPF';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
