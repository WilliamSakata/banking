<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\BuildingBlocks\EntityCapabilities;
use Banking\Account\Model\BuildingBlocks\Identity;
use Banking\Account\Model\BuildingBlocks\Version;
use Exception;
use ReflectionClass;
use Ramsey\Uuid\Uuid;

trait EventSourcingCapabilities
{
    use EntityCapabilities;

    /**
     * @var EventRecordCollection
     */
    private EventRecordCollection $recordedEvents;

    /**
     * @param Identity $identity
     * @param Version $sequenceNumber
     */
    private function __construct(private Identity $identity, private Version $sequenceNumber)
    {
        $this->{$this->getIdentityName()} = $this->identity;
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
     */
    public static function blank(Identity $identity): EventSourcingRoot
    {
        return new static($identity, new Version(0));
    }

    /**
     * @param Identity $identity
     * @param EventRecordCollection $records
     * @return EventSourcingRoot
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
        $record = new EventRecord(Uuid::uuid4()->toString(), $event, $event->getRevision(), );

        $this->applyEvent($record);
        $this->recordEvent($record);
    }

    /**
     * @param EventRecord $record
     */
    private function recordEvent(EventRecord $record)
    {
        $this->recordedEvents[] = $record;
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