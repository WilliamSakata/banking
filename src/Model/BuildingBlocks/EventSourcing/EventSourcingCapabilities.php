<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\BuildingBlocks\Identity;
use Banking\Account\Model\BuildingBlocks\Version;
use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

trait EventSourcingCapabilities
{
    /**
     * @var EventRecordCollection
     */
    private EventRecordCollection $recordedEvents;

    /**
     * @param Identity $identity
     * @param Version $sequenceNumber
     * @throws ReflectionException
     */
    private function __construct(private Identity $identity, private Version $sequenceNumber)
    {
        $this->{$this->getIdentityName()} = $this->identity;
        $this->recordedEvents = new EventRecordCollection();
    }

    /**
     * @return EventRecordCollection
     */
    public function getRecordedEvents(): EventRecordCollection
    {
        return $this->recordedEvents;
    }

    /**
     * @param Identity $identity
     * @return EventSourcingRoot
     * @throws ReflectionException
     */
    public static function blank(Identity $identity): EventSourcingRoot
    {
        return new static($identity, new Version(0));
    }

    /**
     * @param Identity $identity
     * @param EventRecordCollection $records
     * @return EventSourcingRoot
     * @throws ReflectionException
     */
    public static function reconstitute(Identity $identity, EventRecordCollection $records): EventSourcingRoot
    {
        $aggregate = static::blank($identity);

        /** @var EventRecord[] $records */
        foreach ($records as $record) {
            $aggregate->applyEvent($record);
        }

        return $aggregate;
    }

    /**
     * Aplica e salva evento na instância do agregado
     * @param  DomainEvent $event
     * @param  Identity $identity
     * @throws Exception
     */
    protected function when(DomainEvent $event, Identity $identity)
    {
        $record = new EventRecord(
            Uuid::uuid4()->toString(),
            $identity->getValue(),
            $event,
            $event->getRevision(),
            $this->sequenceNumber->getValue(),
            $event::class,
            new DateTimeImmutable(),
            $event->getAggregateType()
        );

        $this->applyEvent($record);
        $this->recordEvent($record);
    }

    /**
     * @param EventRecord $record
     */
    private function recordEvent(EventRecord $record)
    {
        $this->recordedEvents->add($record);
    }

    /**
     * Aplica evento na instância do agregado
     *
     * @param EventRecord $record
     */
    private function applyEvent(EventRecord $record)
    {
        $method = $this->onEventName($record->getDomainEvent());
        $this->$method($record->getDomainEvent());
    }

    /**
     * @param DomainEvent $event
     * @return string
     */
    private function onEventName(DomainEvent $event): string
    {
        return sprintf("on%s", (new ReflectionClass($event))->getShortName());
    }
}