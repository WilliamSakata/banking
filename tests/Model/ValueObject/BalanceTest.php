<?php

namespace Banking\Account\Model\ValueObject;

use Banking\Account\Model\Balance;
use LogicException;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    public function testCreateBalance(): void
    {
        $amount = 99.00;
        $balance = new Balance($amount);

        self::assertEquals($amount, $balance->getAmount());
    }

    public function testInvalidAmountForBalance(): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Invalid amount for balance');

        new Balance(-1.0);
    }
}