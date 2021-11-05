<?php

namespace Banking\Account\Model;

use Exception;
use ReflectionClass;

trait EventSourcingCapabilities
{
    /**
     * Lista de eventos de domínio disparados pelo agregado enquanto objeto em memória
     *
     * @var array
     */
    private array $recordedEvents;
    private Identity $identity;

    /**
     * Construtor deve ser protegido pois o agregado deve ser instanciado através do método
     * blank visando aplicar eventos ao invés de valores
     *
     * @param Identity $identity
     */
    private function __construct(Identity $identity)
    {
        $this->recordedEvents = [];

        $this->identity = $identity;
    }

    /**
     * Recupera Stream eventos (eventos em memória) que ocorreram com o agregado após sua construção.
     *
     * @return array
     */
    public function getRecordedEvents(): array
    {
        return $this->recordedEvents;
    }

    /**
     * Retorna instância do agregado com estado em "branco"
     *
     * @param Identity $identity
     * @return static|EventSourcingRoot
     * @noinspection PhpDocSignatureInspection
     */
    public static function blank(Identity $identity): EventSourcingRoot
    {
        return new static($identity);
    }

    /**
     * Reconstitui o estado do agregado aplicando uma lista de eventos salvos no event store
     *
     * @param Identity              $identity
     * @param EventRecordCollection $records
     * @return static|EventSourcingRoot
     * @noinspection PhpDocSignatureInspection
     */
    public static function reconstitute(Identity $identity, EventRecordCollection $records): EventSourcingRoot
    {
        $entity = static::blank($identity);

        /**
         * @var $record EventRecord
         */
        foreach ($records as $record) {
            $entity->applyEvent($record);
        }

        return $entity;
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
            $event,
            $identity
        );

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
        $method = $this->onEventName($record->getDomainEvent()); // When{EventName}
        $this->$method($record->getDomainEvent());
    }

    /**
     * @param DomainEvent $event
     * @return string
     * @todo Cache de objetos reflection ?
     *
     * https://gist.github.com/mindplay-dk/3359812
     *
     */
    private function onEventName(DomainEvent $event): string
    {
        return sprintf("on%s", (new ReflectionClass($event))->getShortName());
    }
}