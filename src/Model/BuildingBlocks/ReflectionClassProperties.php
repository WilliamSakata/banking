<?php

namespace Banking\Account\Model\BuildingBlocks;

use ReflectionClass;
use ReflectionException;

class ReflectionClassProperties
{
    /**
     * @param mixed $class
     * @param string $propertyName
     * @return bool
     * @throws ReflectionException
     */
    public function exists(mixed $class, string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->getAll($class));
    }

    /**
     * @param mixed $className
     * @return array
     * @throws ReflectionException
     */
    private function getAll(mixed $className): array
    {
        $class = new ReflectionClass($className);

        $result = [];
        foreach ($class->getProperties() as $property) {
            $result[$property->getName()] = $property;
        }

        $parentClass = $class->getParentClass();

        if ($parentClass) {
            $parentProperties = $this->getAll($parentClass->getName());
            if (count($parentProperties) > 0) {
                $result = array_merge($parentProperties, $result);
            }
        }

        return $result;
    }
}