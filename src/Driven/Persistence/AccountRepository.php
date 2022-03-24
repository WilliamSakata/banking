<?php

namespace Banking\Account\Driven\Persistence;

use Banking\Account\Model\Account;
use Banking\Account\Model\AccountRepository as Repository;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventRecord;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventRecordCollection;
use Banking\Account\Model\Cpf;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;
use ReflectionException;

class AccountRepository implements Repository
{
    /**
     * @param MySqlAdapter $adapter
     */
    public function __construct(private MySqlAdapter $adapter)
    {
    }

    /**
     * @param  Cpf $document
     * @return bool
     * @throws DriverException
     * @throws Exception
     */
    public function accountExists(Cpf $document): bool
    {
        $builder = $this->adapter->createQueryBuilder();

        $statement = $builder->select('count(1)')
            ->from('account_events')
            ->where("payload like '%:document%'")
            ->setParameter(':document', $document->getValue())
            ->execute();

        $result = $statement->fetchNumeric();

        if ($result[0] > 1) {
            return true;
        }

        return false;
    }

    /**
     * @param  Cpf $cpf
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

        $factory = new Factory();

        foreach ($result as $item) {
            $collection->add($factory->buildEventRecord($item));
        }

        return Account::replay($cpf, $collection);
    }

    /**
     * @param  Account $account
     * @throws Exception
     */
    public function push(Account $account): void
    {
        try {
            $this->adapter->beginTransaction();

            /**
            * @var EventRecord $recordedEvent
            */
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
