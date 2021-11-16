<?php

namespace Banking\Account\Model\BuildingBlocks\ValueObject;

interface Immutable
{
    /**
     * @param $key
     * @return mixed
     */
    public function __get($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function __set($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function __unset($key);

    /**
     * @param Immutable $obj
     * @return mixed
     */
    public function equals(Immutable $obj): bool;
}