<?php

namespace Banking\Account\Model;

class EventRecord
{
    public function __construct(private DomainEvent $domainEvent, private Identity $identity)
    {
    }

    /**
     * @return DomainEvent
     */
    public function getDomainEvent(): DomainEvent
    {
        return $this->domainEvent;
    }

    /**
     * @return Identity
     */
    public function getIdentity(): Identity
    {
        return $this->identity;
    }
}