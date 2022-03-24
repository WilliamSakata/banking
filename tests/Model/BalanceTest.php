<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\InvalidBalance;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    public function testCreateBalance(): void
    {
        $amount = 99.00;
        $balance = new Balance($amount);

        self::assertEquals($amount, $balance->getValue());
    }

    public function testInvalidAmountForBalance(): void
    {
        $this->expectException(InvalidBalance::class);
        $this->expectErrorMessage('Invalid amount for balance');

        new Balance(-1.0);
    }
}