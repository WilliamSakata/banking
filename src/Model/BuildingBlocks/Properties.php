<?php

namespace Banking\Account\Model\BuildingBlocks;

use ReflectionClass;
use ReflectionException;

class Properties
{
    /**
     * @param $class
     * @param string $propertyName
     * @return bool
     * @throws ReflectionException
     */
    public function exists($class, string $propertyName): bool
    {
        return array_key_exists($propertyName, $this->getAll($class));
    }

    /**
     * @param $className
     * @return array
     * @throws ReflectionException
     */
    private function getAll($className): array
    {
        $ref = new ReflectionClass($className);
        $props = $ref->getProperties();

        $result = [];
        foreach ($props as $prop) {
            $name = $prop->getName();
            $result[$name] = $prop;
        }

        $parentClass = $ref->getParentClass();

        if ($parentClass) {
            $parentProps = $this->getAll($parentClass->getName());
            if (count($parentProps) > 0) {
                $result = array_merge($parentProps, $result);
            }
        }

        return $result;
    }
}