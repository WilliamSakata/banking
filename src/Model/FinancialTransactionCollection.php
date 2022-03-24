<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\Collectible;
use Banking\Account\Model\BuildingBlocks\Collection;

class FinancialTransactionCollection implements Collectible
{
    use Collection;
}
