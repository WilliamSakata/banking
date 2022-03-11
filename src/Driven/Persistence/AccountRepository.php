<?php

namespace Banking\Account\Driven\Persistence;

use Banking\Account\Model\Account;
use Banking\Account\Model\AccountCreated;
use Banking\Account\Model\AccountRepository as Repository;
use Banking\Account\Model\Amount;
use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventRecord;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventRecordCollection;
use Banking\Account\Model\Cpf;
use Banking\Account\Model\Currency;
use Banking\Account\Model\DepositPerformed;
use Banking\Account\Model\FinancialTransaction;
use Banking\Account\Model\WithdrawPerformed;
use DateTimeImmutable;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;
use ReflectionException;
use function DI\add;

class AccountRepository implements Repository
{
    /**
     * @param MySqlAdapter $adapter
     */
    public function __construct(private MySqlAdapter $adapter)
    {
    }

    /**
     * @param Cpf $cpf
     * @return Account
     * @throws DriverException
     * @throws Exception
     * @throws ReflectionException
     * @throws \Exception
     */
    public function pull(Cpf $cpf): Account
    {
        $builder = $this->adapter->createQueryBuilder();

        $statement = $builder->select('*')
            ->from('account_events')
            ->where('aggregate_id = :aggregateId')
            ->orderBy('sequence_number')
            ->setParameter(':aggregateId', $cpf->getValue())
            ->execute();

        $result = $statement->fetchAllAssociative();

        $collection = new EventRecordCollection();

        foreach ($result as $item) {
            $collection->add($this->buildEventRecord($item));
        }

        return Account::reconstitute($cpf, $collection);
    }

    /**
     * @param array $item
     * @return EventRecord
     * @throws \Exception
     */
    private function buildEventRecord(array $item): EventRecord
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
     * @param String $domainName
     * @param String $payload
     * @return DomainEvent
     * @throws \Exception
     */
    private function identifyDomainEvent(string $domainName, string $payload): DomainEvent
    {
        $eventObj = json_decode($payload);

        return match ($domainName) {
            "AccountCreated" => new AccountCreated(
                new Cpf($eventObj->accountId->cpf),
                new Amount($eventObj->amount->value, new Currency($eventObj->amount->currency)),
                new DateTimeImmutable($eventObj->occurredOn)
            ),
            "WithdrawPerformed" => new WithdrawPerformed(
                new Cpf($eventObj->accountId->cpf),
                new FinancialTransaction(
                    new DateTimeImmutable($eventObj->financialTransaction->createdAt),
                    new Amount(
                        $eventObj->financialTransaction->amount->value,
                        new Currency($eventObj->financialTransaction->amount->currency)
                    ),
                    $eventObj->financialTransaction->type
                )
            ),
            "DepositPerformed" => new DepositPerformed(
                new Cpf($eventObj->accountId->cpf),
                new FinancialTransaction(
                    new DateTimeImmutable($eventObj->financialTransaction->createdAt),
                    new Amount(
                        $eventObj->financialTransaction->amount->value,
                        new Currency($eventObj->financialTransaction->amount->currency)
                    ),
                    $eventObj->financialTransaction->type
                )
            ),
            default => throw new \DomainException("Event not supported"),
        };
    }

    /**
     * @param Account $account
     * @throws Exception
     */
    public function push(Account $account): void
    {
        try {
            $this->adapter->beginTransaction();

            /** @var EventRecord $recordedEvent */
            foreach ($account->getRecordedEvents()->getList() as $recordedEvent) {

                $builder = $this->adapter->createQueryBuilder();

                $builder->insert('account_events')
                    ->setValue('code', ':code')
                    ->setValue('aggregate_type', ':aggregateType')
                    ->setValue('aggregate_id', ':aggregateId')
                    ->setValue('event_name', ':eventName')
                    ->setValue('sequence_number', ':sequenceNumber')
                    ->setValue('revision', ':revision')
                    ->setValue('payload', ':payload')
                    ->setValue('occurred_on', ':occurredOn')
                    ->setParameter(':code', $recordedEvent->getId())
                    ->setParameter(':aggregateType', $recordedEvent->getAggregateType())
                    ->setParameter(':aggregateId', $recordedEvent->getIdentity())
                    ->setParameter(':eventName', $recordedEvent->getEventName())
                    ->setParameter(':sequenceNumber', $recordedEvent->getSequenceNumber())
                    ->setParameter(':revision', $recordedEvent->getRevision())
                    ->setParameter(':payload', json_encode($recordedEvent->getDomainEvent()->toArray()))
                    ->setParameter(':occurredOn', $recordedEvent->getOccurredOn()->format('Y-m-d H:i:s'))
                    ->execute();
            }

            $this->adapter->commit();
        } catch (Exception $ex) {
            $this->adapter->rollback();
            throw $ex;
        }
    }
}