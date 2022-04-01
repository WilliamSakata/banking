<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\DifferentCurrencies;
use PHPUnit\Framework\TestCase;

final class AmountTest extends TestCase
{
    private Currency $brl;

    protected function setUp(): void
    {
        $this->brl = new Currency('BRL');
    }

    /**
     * @Given an amount
     * @When a new amount with the same currency is added to the current amount
     * @The current amount
     * @Will have its value plus the amount added
     */
    public function testAddValue(): void
    {
        $amount = new Amount(1.0, $this->brl);
        $amount = $amount->add(new Amount(0.1, $this->brl));

        self::assertEquals(1.1, $amount->getValue());
        self::assertEquals($this->brl, $amount->getCurrency());
    }

    /**
     * @Given an amount
     * @When a new amount with different currency is added to the current amount
     * @An exception
     * @Will be thrown
     */
    public function testCurrenciesCannotBeDifferent(): void
    {
        $this->expectException(DifferentCurrencies::class);
        $this->expectErrorMessage('Currencies cannot be different');

        $amount = new Amount(1.0, $this->brl);
        $amount->add(new Amount(0.1, new Currency('USD')));
    }

    /**
     * @Given an amount
     * @When a new amount with the same currency is subbed from the current amount
     * @Then the value of the current amount
     * @Will be the value of the current amount minus the value of the new amount
     */
    public function testSubValue(): void
    {
        $amount = new Amount(1.0, $this->brl);
        $amount = $amount->sub(new Amount(0.1, $this->brl));

        self::assertEquals(0.9, $amount->getValue());
        self::assertEquals($this->brl, $amount->getCurrency());
    }

    /**
     * @Given an amount
     * @With value equals zero
     * @Then the method isZero
     * @Will return true
     */
    public function testIsZero(): void
    {
        $amount = new Amount(0.0, $this->brl);

        self::assertTrue($amount->isZero());
        self::assertEquals(0.0, $amount->getValue());
    }
}
