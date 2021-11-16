<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use DateTimeImmutable;

class EventRecord
{
    /**
     * @param string $id
     * @param string $identity
     * @param DomainEvent $domainEvent
     * @param int $revision
     * @param int $sequenceNumber
     * @param string $eventName
     * @param DateTimeImmutable $occurredOn
     * @param string $aggregateType
     */
    public function __construct(
        private string            $id,
        private string            $identity,
        private DomainEvent       $domainEvent,
        private int               $revision,
        private int               $sequenceNumber,
        private string            $eventName,
        private DateTimeImmutable $occurredOn,
        private string            $aggregateType
    )
    {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return DomainEvent
     */
    public function getDomainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }

    /**
     * @return int
     */
    public function getRevision(): int
    {
        return $this->revision;
    }

    /**
     * @return int
     */
    public function getSequenceNumber(): int
    {
        return $this->sequenceNumber;
    }

    /**
     * @return string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @return string
     */
    public function getAggregateType(): string
    {
        return $this->aggregateType;
    }
}