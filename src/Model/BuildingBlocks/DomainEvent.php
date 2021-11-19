<?php

namespace Banking\Account\Model\BuildingBlocks;

interface DomainEvent
{
    /**
     * @return int
     */
    public  function getRevision(): int;

    /**
     * @return string
     */
    public function getAggregateType(): string;

    /**
     * @return array
     */
    public function toArray(): array;
}