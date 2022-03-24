<?php

namespace Banking\Account\Model\errors;

use DomainException;

final class InsufficientBalance extends DomainException
{
    private const MESSAGE = 'Insufficient balance';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }
}
