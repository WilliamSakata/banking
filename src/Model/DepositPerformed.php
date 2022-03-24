<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\errors\InvalidFinancialTransactionType;
use ReflectionClass;

class DepositPerformed implements DomainEvent
{
    private const REVISION = 1;
    private const AGGREGATE_TYPE = 'Account';

    public function __construct(private Cpf $accountId, private FinancialTransaction $financialTransaction)
    {
        if ($this->financialTransaction->getType() != FinancialTransactionType::CREDIT) {
            $reflected = new ReflectionClass($this);

            throw new InvalidFinancialTransactionType(
                FinancialTransactionType::DEBIT->value,
                $reflected->getShortName()
            );
        }
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
