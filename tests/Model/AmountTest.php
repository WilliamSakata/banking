<?php

namespace Banking\Account\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    private const VALID_VALUE = 1239;
    private const INVALID_VALUE = -1;

    public function testIfValueIsTheProvided():void {
        $amount =  new Amount(self::VALID_VALUE);
        static::assertInstanceOf(Amount::class, $amount);
        static::assertEquals(self::VALID_VALUE, $amount->getValue());
    }

    public function testCreateFail():void {
        static::expectException(InvalidArgumentException::class);

        new Amount(self::INVALID_VALUE);
    }
}
