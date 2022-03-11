<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;

final class WithdrawPerformed implements DomainEvent
{
    private const REVISION = 1;
    private const AGGREGATE_TYPE = 'AccountDebited';

    /**
     * @param Cpf $accountId
     * @param FinancialTransaction $financialTransaction
     */
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

    /**
     * @return int
     */
    public function getRevision(): int
    {
        return self::REVISION;
    }

    /**
     * @return string
     */
    public function getAggregateType(): string
    {
        return self::AGGREGATE_TYPE;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'accountId' => $this->accountId->toArray(),
            'financialTransaction' => $this->financialTransaction->toArray()
        ];
    }
}