<?php

namespace Banking\Account\Model\ValueObject;

use LogicException;
use PHPUnit\Framework\TestCase;

final class AmountTest extends TestCase
{
    private Currency $brl;

    protected function setUp(): void
    {
        $this->brl = new Currency('BRL');
    }

    public function testAddValue(): void
    {
        $amount = new Amount(1.0, $this->brl);
        $amount = $amount->add(new Amount(0.1, $this->brl));

        self::assertEquals(1.1, $amount->getValue());
        self::assertEquals($this->brl, $amount->getCurrency());
    }

    public function testCurrenciesCannotBeDifferent(): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage('Currencies cannot be different');

        $amount = new Amount(1.0, $this->brl);
        $amount->add(new Amount(0.1, new Currency('USD')));
    }

    public function testSubValue(): void
    {
        $amount = new Amount(1.0, $this->brl);
        $amount = $amount->sub(new Amount(0.1, $this->brl));

        self::assertEquals(0.9, $amount->getValue());
        self::assertEquals($this->brl, $amount->getCurrency());
    }

    public function testIsZero(): void
    {
        $amount = new Amount(0.0, $this->brl);

        self::assertTrue($amount->isZero());
        self::assertEquals(0.0, $amount->getValue());
    }
}
