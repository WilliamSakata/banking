<?php

namespace Banking\Account\Query\Balance;

use Banking\Account\Model\ValueObject\Amount;
use Banking\Account\Model\Cpf;

class BalanceResult
{
    /**
     * @param Cpf $document
     * @param Amount $balance
     */
    public function __construct(private Cpf $document, private Amount $balance)
    {
    }

    /**
     * @return Cpf
     */
    public function getDocument(): Cpf
    {
        return $this->document;
    }

    /**
     * @return Amount
     */
    public function getBalance(): Amount
    {
        return $this->balance;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'document' => $this->document->getValue(),
            'balance' => $this->balance->getValue()
        ];
    }
}