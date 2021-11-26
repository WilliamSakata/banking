<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\EventSourcing\Collectible;
use Banking\Account\Model\BuildingBlocks\EventSourcing\Collection;

class FinancialTransactionCollection implements Collectible
{
    use Collection;
}