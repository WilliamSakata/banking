<?php

namespace Banking\Account\Query\Balance;

use Banking\Account\Model\Amount;
use Banking\Account\Model\Cpf;

class Balance
{
    /**
     * @param Cpf $document
     */
    public function __construct(private Cpf $document)
    {
    }

    /**
     * @return Cpf
     */
    public function getDocument(): Cpf
    {
        return $this->document;
    }
}