<?php

namespace Banking\Account\Model\errors;

use DomainException;

final class DepositLimitReached extends DomainException
{
    private const MESSAGE = 'Deposit limit reached. The max value allowed is %d';

    public function __construct(int $limit)
    {
        $message = sprintf(self::MESSAGE, $limit);

        parent::__construct($message);
    }
}
