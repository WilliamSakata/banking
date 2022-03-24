<?php

namespace Banking\Account\Model\errors;

use LogicException;

final class InvalidFinancialTransactionType extends LogicException
{
    private const MESSAGE = 'Invalid financial transaction type %s for %s event';

    public function __construct(string $type, string $event)
    {
        $message = sprintf(self::MESSAGE, $type, $event);

        parent::__construct($message);
    }
}
