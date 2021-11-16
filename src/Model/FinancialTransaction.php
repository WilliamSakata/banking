<?php

namespace Banking\Account\Model;

use DateTimeImmutable;

final class FinancialTransaction
{
    /**
     * @param DateTimeImmutable $date
     * @param Amount $transactionValue
     */
    public function __construct(private DateTimeImmutable $date, private Amount $transactionValue)
    {
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    /**
     * @return Amount
     */
    public function getTransactionValue(): Amount
    {
        return $this->transactionValue;
    }
}