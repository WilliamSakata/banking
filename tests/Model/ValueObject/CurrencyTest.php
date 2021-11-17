<?php

namespace Banking\Account\Model\ValueObject;

use Banking\Account\Model\Currency;
use LogicException;
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
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Invalid currency xpto');

        new Currency('xpto');
    }
}
