<?php

namespace Banking\Account\Query\AccountStatement;

use Banking\Account\Model\Cpf;

class AccountStatement
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