<?php

namespace Banking\Account\Model\BuildingBlocks;

trait Collection
{
    /**
     * @var array
     */
    protected array $list = [];

    /**
     * @param $item
     */
    public function add($item): void
    {
        $this->list[] = $item;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->list);
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->list;
    }

    public function clear(): void
    {
        $this->list = [];
    }
}