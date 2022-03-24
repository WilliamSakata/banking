<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\DomainEvent;
use Banking\Account\Model\BuildingBlocks\EntityCapabilities;
use Banking\Account\Model\BuildingBlocks\Identity;
use Banking\Account\Model\BuildingBlocks\Version;
use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Uuid;
use ReflectionClass;
use ReflectionException;

trait EventSourcingCapabilities
{
    use EntityCapabilities;

    /**
     * @var EventRecordCollection
     */
    private EventRecordCollection $recordedEvents;

    /**
     * @param  Identity $identity
     * @param  Version  $sequenceNumber
     * @throws ReflectionException
     */
    private function __construct(private Identity $identity, private Version $sequenceNumber)
    {
        $this->{$this->getIdentityPropertyName()} = $this->identity;
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
     * @param  Identity $identity
     * @return EventSourcingRoot
     * @throws ReflectionException
     */
    public static function blank(Identity $identity): EventSourcingRoot
    {
        return new self($identity, new Version(0));
    }

    /**
     * @param  Identity              $identity
     * @param  EventRecordCollection $records
     * @return EventSourcingRoot
     * @throws ReflectionException
     */
    public static function replay(Identity $identity, EventRecordCollection $records): EventSourcingRoot
    {
        $aggregate = static::blank($identity);

        foreach ($records->getList() as $record) {
            $aggregate->applyEvent($record);
        }

        return $aggregate;
    }

    /**
     * @param  DomainEvent $event
     * @throws Exception
     */
    protected function trigger(DomainEvent $event)
    {
        $this->sequenceNumber = $this->sequenceNumber->next();

        $record = new EventRecord(
            Uuid::uuid4()->toString(),
            $this->getIdentity(),
            $event,
            $event->getRevision(),
            $this->sequenceNumber->getValue(),
            (new ReflectionClass($event::class))->getShortName(),
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
     * @param EventRecord $record
     */
    private function applyEvent(EventRecord $record)
    {
        $method = $this->onEvent($record->getDomainEvent());
        $this->$method($record->getDomainEvent());
    }

    /**
     * @param  DomainEvent $event
     * @return string
     */
    private function onEvent(DomainEvent $event): string
    {
        return sprintf("on%s", (new ReflectionClass($event))->getShortName());
    }
}
