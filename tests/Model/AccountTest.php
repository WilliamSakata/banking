<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Deposit\Deposit;
use Banking\Account\Command\Withdraw\Withdraw;
use Banking\Account\Model\BuildingBlocks\Version;
use DomainException;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    private const DOCUMENT = '353.934.420-92';
    private const CURRENCY = 'BRL';

    private Cpf $cpf;
    private Currency $currency;

    public function setUp(): void
    {
        $this->cpf = new Cpf(self::DOCUMENT);
        $this->currency = new Currency(self::CURRENCY);
    }

    /**
     * @Given valid values
     * @And the right order of events
     * @The workflow to create account, deposit and withdraw
     * @Will execute without errors
     */
    public function testSuccessfulUseCases(): void
    {
        /** @var Account $account */
        $account = Account::blank(new Cpf(self::DOCUMENT));
        $account->create();

        $deposit = new Deposit($this->cpf, new Amount(100, $this->currency));
        $account->deposit($deposit);

        $withDraw = new Withdraw(new Cpf(self::DOCUMENT), new Amount( 10, new Currency('BRL')));
        $account->withDraw($withDraw);

        static::assertEquals('353.934.420-92', $account->getDocument()->getValue());
        static::assertEquals(90, $account->getBalance()->getAmount());
        static::assertEquals(new Version(3), $account->getSequenceNumber());

        static::assertInstanceOf(
            AccountCreated::class, $account->getRecordedEvents()->getList()[0]->getDomainEvent()
        );

        static::assertEquals(
            new Amount(0, new Currency('BRL')),
            $account->getRecordedEvents()->getList()[0]->getDomainEvent()->getamount()
        );

        static::assertInstanceOf(
            DepositPerformed::class,
            $account->getRecordedEvents()->getList()[1]->getDomainEvent()
        );

        static::assertEquals(
            new Amount(100, new Currency('BRL')),
            $account->getRecordedEvents()->getList()[1]->getDomainEvent()->getFinancialTransaction()->getAmount()
        );

        static::assertEquals(
            'C',
            $account->getRecordedEvents()->getList()[1]->getDomainEvent()->getFinancialTransaction()->getType()
        );

        static::assertInstanceOf(
            WithdrawPerformed::class,
            $account->getRecordedEvents()->getList()[2]->getDomainEvent()
        );

        static::assertEquals(
            new Amount(10, new Currency('BRL')),
            $account->getRecordedEvents()->getList()[2]->getDomainEvent()->getFinancialTransaction()->getAmount()
        );

        static::assertEquals(
            'D',
            $account->getRecordedEvents()->getList()[2]->getDomainEvent()->getFinancialTransaction()->getType()
        );
    }

    /**
     * @Given a deposit use case
     * @With amount bigger than 10000
     * @Will throw an Exception
     */
    public function testDepositBeyondLimit(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Deposit limit reached. The max value allowed is 10000');

        /** @var Account $account */
        $account = Account::blank(new Cpf(self::DOCUMENT));
        $account->create();

        $deposit = new Deposit($this->cpf, new Amount(10001, $this->currency));
        $account->deposit($deposit);
    }

    /**
     * @Given a case where try to withdraw without balance
     * @Will throw an Exception
     */
    public function testWithdrawWithoutBalance(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Insufficient balance');

        /** @var Account $account */
        $account = Account::blank(new Cpf(self::DOCUMENT));
        $account->create();

        $withDraw = new Withdraw(new Cpf(self::DOCUMENT), new Amount( 10, new Currency('BRL')));
        $account->withDraw($withDraw);
    }

    public function test(): void
    {
        /** @var Account $account */
        $account = Account::blank(new Cpf(self::DOCUMENT));
        $account->create();

        $deposit = new Deposit($this->cpf, new Amount(100, $this->currency));
        $account->deposit($deposit);

        $withDraw = new Withdraw(new Cpf(self::DOCUMENT), new Amount( 10, new Currency('BRL')));
        $account->withDraw($withDraw);

        static::assertEquals(new Amount(100, $this->currency), $account->getFinancialTransactionCollection()->getList()[0]->getAmount());
        static::assertEquals('C', $account->getFinancialTransactionCollection()->getList()[0]->getType());
        static::assertEquals(new Amount(10, $this->currency), $account->getFinancialTransactionCollection()->getList()[1]->getAmount());
        static::assertEquals('D', $account->getFinancialTransactionCollection()->getList()[1]->getType());
    }
}
