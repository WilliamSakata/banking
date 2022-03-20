<?php

namespace Banking\Account\Model\BuildingBlocks;

interface Collectible
{
    public function clear(): void;

    /**
     * @param mixed $item
     */
    public function add(mixed $item): void;

    /**
     * @return int
     */
    public function getCount(): int;

    /**
     * @return array
     */
    public function getList(): array;
}