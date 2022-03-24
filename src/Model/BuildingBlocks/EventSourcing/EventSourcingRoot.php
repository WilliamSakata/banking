<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\Entity;
use Banking\Account\Model\BuildingBlocks\Identity;

interface EventSourcingRoot extends Entity
{
    /**
     * @return EventRecordCollection
     */
    public function getRecordedEvents(): EventRecordCollection;

    /**
     * @param  Identity $identity
     * @return EventSourcingRoot
     */
    public static function blank(Identity $identity): EventSourcingRoot;

    /**
     * @param  Identity              $identity
     * @param  EventRecordCollection $records
     * @return EventSourcingRoot
     */
    public static function replay(Identity $identity, EventRecordCollection $records): EventSourcingRoot;
}
