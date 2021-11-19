<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use DateTimeImmutable;

class AccountCreated implements DomainEvent
{
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
        return 1;
    }

    /**
     * @return string
     */
    public function getAggregateType(): string
    {
        return AccountCreated::class;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
          'accountId' => $this->accountId,
          'amount' => $this->amount,
          'occurredOn' => $this->occurredOn
        ];
    }
}