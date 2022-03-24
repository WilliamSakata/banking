<?php

namespace Banking\Account\Command\Withdraw;

use Banking\Account\Model\Amount;
use Banking\Account\Model\Cpf;

class Withdraw
{
    /**
     * @var Cpf
     */
    private Cpf $document;

    /**
     * @var Amount
     */
    private Amount $amount;

    /**
     * @param Cpf    $document
     * @param Amount $amount
     */
    public function __construct(Cpf $document, Amount $amount)
    {
        $this->document = $document;
        $this->amount = $amount;
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
    public function getAmount(): Amount
    {
        return $this->amount;
    }
}
