<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Deposit\Deposit;
use Banking\Account\Command\Withdraw\Withdraw;
use DomainException;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private const DOCUMENT = '353.934.420-92';
    private const BALANCE = 123;
    private const VALID_VALUE = 12;
    private const INVALID_VALUE = 10001;

    public function testCreateSuccess(): void
    {
        $account = new Account(new Cpf(self::DOCUMENT), new Amount(self::BALANCE));
        static::assertInstanceOf(Account::class, $account);
    }

    public function testWithdrawSuccess(): void
    {
        $account = new Account(new Cpf(self::DOCUMENT), new Amount(self::BALANCE));
        $newBalance = $account->getBalance()->getValue() - self::VALID_VALUE;
        $withdrawUseCase = new Withdraw(new Cpf(self::DOCUMENT), new Amount(self::VALID_VALUE));
        $account->withDraw($withdrawUseCase);

        static::assertEquals($newBalance, $account->getBalance()->getValue());
    }

    public function testWithDrawFailure(): void
    {
        static::expectException(DomainException::class);

        $account = new Account(new Cpf(self::DOCUMENT), new Amount(self::BALANCE));
        $withdrawUseCase = new Withdraw(new Cpf(self::DOCUMENT), new Amount(self::INVALID_VALUE));
        $account->withDraw($withdrawUseCase);
    }

    public function testDepositSuccess(): void
    {
        $account = new Account(new Cpf(self::DOCUMENT), new Amount(self::BALANCE));
        $newBalance = $account->getBalance()->getValue() + self::VALID_VALUE;
        $depositUseCase = new Deposit(new Cpf(self::DOCUMENT), new Amount(self::VALID_VALUE));
        $account->deposit($depositUseCase);

        static::assertEquals($newBalance, $account->getBalance()->getValue());
    }

    public function testDepositFailure(): void
    {
        static::expectException(DomainException::class);
        $account = new Account(new Cpf(self::DOCUMENT), new Amount(self::BALANCE));
        $depositUseCase = new Deposit(new Cpf(self::DOCUMENT), new Amount(self::INVALID_VALUE));
        $account->deposit($depositUseCase);
    }
}
