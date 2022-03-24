<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use DateTimeImmutable;

class AccountCreated implements DomainEvent
{
    private const AGGREGATE_TYPE = 'Account';
    private const REVISION = 1;

    /**
     * @param Cpf               $accountId
     * @param Amount            $amount
     * @param DateTimeImmutable $occurredOn
     */
    public function __construct(private Cpf $accountId, private Amount $amount, private DateTimeImmutable $occurredOn)
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
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
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
            'amount' => $this->amount->toArray(),
            'occurredOn' => $this->occurredOn->format('Y-m-d H:i:s')
        ];
    }
}
