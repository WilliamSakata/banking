<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use DateTimeImmutable;

class AccountCreated implements DomainEvent
{
    private const AGGREGATE_TYPE = 'AccountCreated';
    private const REVISION = 1;

    private Cpf $accountId;
    private Amount $amount;
    private DateTimeImmutable $occurredOn;

    /**
     * @param Cpf $accountId
     * @param Amount $amount
     * @param DateTimeImmutable $occurredOn
     */
    public function __construct(Cpf $accountId, Amount $amount, DateTimeImmutable $occurredOn)
    {
        $this->accountId = $accountId;
        $this->amount = $amount;
        $this->occurredOn = $occurredOn;
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