<?php

namespace Banking\Account\Driven\Persistence;

use Banking\Account\Model\AccountCreated;
use Banking\Account\Model\Amount;
use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventRecord;
use Banking\Account\Model\Cpf;
use Banking\Account\Model\Currency;
use Banking\Account\Model\DepositPerformed;
use Banking\Account\Model\FinancialTransaction;
use Banking\Account\Model\FinancialTransactionType;
use Banking\Account\Model\WithdrawPerformed;
use DateTimeImmutable;
use DomainException;
use Exception;

class Factory
{
    /**
     * @param  array $item
     * @return EventRecord
     * @throws Exception
     */
    public function buildEventRecord(array $item): EventRecord
    {
        $id = $item["code"];
        $identity = $item["aggregate_id"];
        $domainEvent = $this->identifyDomainEvent($item["event_name"], $item["payload"]);
        $revision = $item["revision"];
        $sequenceNumber = $item["sequence_number"];
        $eventName = $item["event_name"];
        $occurredOn = new DateTimeImmutable($item["occurred_on"]);
        $aggregateType = $item["aggregate_type"];

        return new EventRecord(
            $id,
            $identity,
            $domainEvent,
            $revision,
            $sequenceNumber,
            $eventName,
            $occurredOn,
            $aggregateType
        );
    }

    /**
     * @param  String $eventName
     * @param  String $payload
     * @return DomainEvent
     * @throws Exception
     */
    private function identifyDomainEvent(string $eventName, string $payload): DomainEvent
    {
        $eventObj = json_decode($payload);

        return match ($eventName) {
            "AccountCreated" => new AccountCreated(
                new Cpf($eventObj->accountId->cpf),
                new Amount($eventObj->amount->value, new Currency($eventObj->amount->currency)),
                new DateTimeImmutable($eventObj->occurredOn)
            ),
            "WithdrawPerformed" => new WithdrawPerformed(
                new Cpf($eventObj->accountId->cpf),
                new FinancialTransaction(
                    new Amount(
                        $eventObj->financialTransaction->amount->value,
                        new Currency($eventObj->financialTransaction->amount->currency)
                    ),
                    new DateTimeImmutable($eventObj->financialTransaction->createdAt),
                    FinancialTransactionType::from($eventObj->financialTransaction->type)
                )
            ),
            "DepositPerformed" => new DepositPerformed(
                new Cpf($eventObj->accountId->cpf),
                new FinancialTransaction(
                    new Amount(
                        $eventObj->financialTransaction->amount->value,
                        new Currency($eventObj->financialTransaction->amount->currency)
                    ),
                    new DateTimeImmutable($eventObj->financialTransaction->createdAt),
                    FinancialTransactionType::from($eventObj->financialTransaction->type)
                )
            ),
            default => throw new DomainException("Event not supported"),
        };
    }
}
