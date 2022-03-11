<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;

class DepositPerformed implements DomainEvent
{
    private const REVISION = 1;
    private const AGGREGATE_TYPE = 'AccountCredited';

    public function __construct(private Cpf $accountId, private FinancialTransaction $financialTransaction)
    {
    }

    /**
     * @return Cpf
     */
    public function getAccountId(): Cpf
    {
        return $this->accountId;
    }

    /**
     * @return FinancialTransaction
     */
    public function getFinancialTransaction(): FinancialTransaction
    {
        return $this->financialTransaction;
    }

    public function getRevision(): int
    {
        return self::REVISION;
    }

    public function getAggregateType(): string
    {
        return self::AGGREGATE_TYPE;
    }

    public function toArray(): array
    {
        return [
            'accountId' => $this->accountId->toArray(),
            'financialTransaction' => $this->financialTransaction->toArray()
        ];
    }
}