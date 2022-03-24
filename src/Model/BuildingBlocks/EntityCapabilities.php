<?php

namespace Banking\Account\Model\BuildingBlocks;

use ReflectionException;
use RuntimeException;

trait EntityCapabilities
{
    /**
     * @return Identity
     * @throws ReflectionException
     */
    public function getIdentity(): Identity
    {
        $result = $this->{$this->getIdentityPropertyName()};

        if ($result && is_a($result, Identity::class)) {
            return $result;
        }

        throw new RuntimeException('Identity not defined');
    }

    /**
     * @return string
     * @throws ReflectionException
     */
    public function getIdentityPropertyName(): string
    {
        $result = $this->getIdentityProperty();

        if ($result && (new ReflectionClassProperties())->exists($this, $result)) {
            return $result;
        }

        throw new RuntimeException('Undefined identity name');
    }

    /**
     * @param  Entity $entity
     * @return bool
     * @throws ReflectionException
     */
    public function equals(Entity $entity): bool
    {
        return $this->getIdentity()->equals($entity->getIdentity());
    }

    abstract public function getIdentityProperty();
}
