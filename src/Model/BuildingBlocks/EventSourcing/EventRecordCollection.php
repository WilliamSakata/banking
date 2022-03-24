<?php

namespace Banking\Account\Model\BuildingBlocks\EventSourcing;

use Banking\Account\Model\BuildingBlocks\Collectible;
use Banking\Account\Model\BuildingBlocks\Collection;

class EventRecordCollection implements Collectible
{
    use Collection;
}
