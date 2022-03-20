<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Deposit\Deposit;
use Banking\Account\Command\Withdraw\Withdraw;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventSourcingCapabilities;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventSourcingRoot;
use Banking\Account\Model\BuildingBlocks\Version;
use DateTimeImmutable;
use DomainException;
use Exception;

final class Account implements EventSourcingRoot
{
    use EventSourcingCapabilities;

    private const DEPOSIT_LIMIT = 10000;
    private const IDENTITY = 'document';

    /**
     * @var Cpf
     */
    private Cpf $document;

    /**
     * @var Balance
     */
    private Balance $balance;

    /**
     * @var FinancialTransactionCollection
     */
    private FinancialTransactionCollection $financialTransactionCollection;

    /**
     * @throws Exception
     */
    public function create(): void
    {
        $event = new AccountCreated(
            $this->document,
            new Amount(0, new Currency('BRL')),
            new DateTimeImmutable()
        );

        $this->trigger($event);
    }

    /**
     * @param AccountCreated $accountCreated
     * @noinspection PhpUnused
     */
    public function onAccountCreated(AccountCreated $accountCreated): void
    {
        $this->sequenceNumber = $this->getSequenceNumber()->next();
        $this->document = $accountCreated->getAccountId();
        $this->balance = new Balance($accountCreated->getAmount()->getValue());
        $this->financialTransactionCollection = new FinancialTransactionCollection();
    }

    /**
     * @param Withdraw $withdraw
     * @throws Exception
     */
    public function withDraw(Withdraw $withdraw): void
    {
        if ($this->withoutBalance($withdraw->getAmount())) {
            throw new DomainException('Insufficient balance');
        }

        $amount = new Amount($withdraw->getAmount()->getValue(), $withdraw->getAmount()->getCurrency());

        $financialTransaction = new FinancialTransaction(
            new DateTimeImmutable(),
            $amount,
            FinancialTransactionType::DEBIT
        );

        $event = new WithdrawPerformed($withdraw->getDocument(), $financialTransaction);
        $this->trigger($event);
    }

    /**
     * @param WithdrawPerformed $withdrawPerformed
     * @noinspection PhpUnused
     */
    public function onWithdrawPerformed(WithdrawPerformed $withdrawPerformed): void
    {
        $this->sequenceNumber = $this->getSequenceNumber()->next();
        $this->document = $withdrawPerformed->getAccountId();
        $newBalance = $this->balance->getValue() - $withdrawPerformed->getFinancialTransaction()->getAmount()->getValue();
        $this->balance = new Balance($newBalance);
        $this->financialTransactionCollection->add($withdrawPerformed->getFinancialTransaction());
    }

    /**
     * @param Amount $value
     * @return bool
     */
    private function withoutBalance(Amount $value): bool
    {
        if ($this->balance->getValue() < $value->getValue()) {
            return true;
        }

        return false;
    }

    /**
     * @param Deposit $deposit
     * @throws Exception
     */
    public function deposit(Deposit $deposit): void
    {
        if ($deposit->getAmount()->getValue() > self::DEPOSIT_LIMIT) {
            throw new DomainException('Deposit limit reached. The max value allowed is 10000');
        }

        $amount = new Amount($deposit->getAmount()->getValue(), $deposit->getAmount()->getCurrency());

        $financialTransaction = new FinancialTransaction(new DateTimeImmutable(), $amount, FinancialTransactionType::CREDIT);

        $event = new DepositPerformed($deposit->getDocument(), $financialTransaction);

        $this->trigger($event);
    }

    /**
     * @param DepositPerformed $depositPerformed
     * @noinspection PhpUnused
     */
    public function onDepositPerformed(DepositPerformed $depositPerformed): void
    {
        $this->sequenceNumber = $this->getSequenceNumber()->next();
        $this->document = $depositPerformed->getAccountId();
        $newBalance = $this->balance->getValue() + $depositPerformed->getFinancialTransaction()->getAmount()->getValue();
        $this->balance = new Balance($newBalance);
        $this->financialTransactionCollection->add($depositPerformed->getFinancialTransaction());
    }

    /**
     * @return Cpf
     */
    public function getDocument(): Cpf
    {
        return $this->document;
    }

    /**
     * @return Balance
     */
    public function getBalance(): Balance
    {
        return $this->balance;
    }

    /**
     * @return FinancialTransactionCollection
     */
    public function getFinancialTransactionCollection(): FinancialTransactionCollection
    {
        return $this->financialTransactionCollection;
    }

    /**
     * @return Version
     */
    public function getSequenceNumber(): Version
    {
        return $this->sequenceNumber;
    }

    /**
     * @return string
     */
    protected function getIdentityProperty(): string
    {
        return self::IDENTITY;
    }
}