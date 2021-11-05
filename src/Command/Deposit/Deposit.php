<?php

namespace Banking\Account\Command\Deposit;

use Banking\Account\Model\Amount;
use Banking\Account\Model\Cpf;

class Deposit
{
    /**
     * @param Amount $amount
     * @param Cpf $document
     */
    public function __construct(private Cpf $document, private Amount $amount)
    {
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return Cpf
     */
    public function getDocument(): Cpf
    {
        return $this->document;
    }
}