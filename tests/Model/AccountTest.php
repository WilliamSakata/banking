<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Withdraw\Withdraw;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private const DOCUMENT = '353.934.420-92';
    private const BALANCE = 123;
    private const VALID_VALUE = 12;
    private const INVALID_VALUE = 10001;

    public function testCreateAccount(): void
    {
        /*$this->expectException(DomainException::class);
        $this->expectExceptionMessage('Insufficient balance');*/

        /** @var Account $account */
        $account = Account::blank(new Cpf(self::DOCUMENT));
        $account->create();
        $withDraw = new Withdraw(new Cpf(self::DOCUMENT), new Amount(10.0, new Currency('BRL')));
        $account->withDraw($withDraw);

        static::assertEquals('353.934.420-92', $account->getDocument()->getValue());
        static::assertEquals(90, $account->getBalance()->getAmount());
    }
}
