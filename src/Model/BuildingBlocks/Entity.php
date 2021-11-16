<?php

namespace Banking\Account\Model\BuildingBlocks;

interface Entity
{
    /**
     * @return Identity
     */
    public function getIdentity(): Identity;

    /**
     * @return string
     */
    public function getIdentityName(): string;

    /**
     * @param Entity $entity
     * @return bool
     */
    public function equals(Entity $entity): bool;
}