<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\InvalidCurrency;
use PHPUnit\Framework\TestCase;

final class CurrencyTest extends TestCase
{
    public function testWhenTryingToCreateValidCurrency(): void
    {
        $currency = new Currency('brl');

        self::assertEquals('BRL', $currency->getValue());
    }

    public function testInvalidCurrency(): void
    {
        $this->expectException(InvalidCurrency::class);
        $this->expectErrorMessage('Invalid currency xpto');

        new Currency('xpto');
    }
}
