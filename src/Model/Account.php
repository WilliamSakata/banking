<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Deposit\Deposit;
use Banking\Account\Command\Withdraw\Withdraw;
use Banking\Account\Model\BuildingBlocks\EntityCapabilities;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventSourcingCapabilities;
use Banking\Account\Model\BuildingBlocks\EventSourcing\EventSourcingRoot;
use Banking\Account\Model\BuildingBlocks\Identity;
use DateTimeImmutable;
use DomainException;
use Exception;

final class Account implements EventSourcingRoot
{
    use EventSourcingCapabilities;
    use EntityCapabilities;

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

    //@ToDo Colocar uma coleção de financial transaction e a cada evento adicionar na lista

    /**
     * @throws Exception
     */
    public function create(): void
    {
        $event = new AccountCreated($this->document, new Amount(100, new Currency('BRL')), new DateTimeImmutable());

        $this->when($event, $this->identity);
    }

    /**
     * @param AccountCreated $accountCreated
     * @noinspection PhpUnused
     */
    public function onAccountCreated(AccountCreated $accountCreated): void
    {
        $this->document = $accountCreated->getAccountId();
        $this->balance = new Balance($accountCreated->getAmount()->getValue());
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

        $financialTransaction = new FinancialTransaction(new DateTimeImmutable(), $amount, FinancialTransactionType::DEBIT);

        $event = new WithdrawPerformed($withdraw->getDocument(), $financialTransaction);
        $this->when($event, $this->identity);
    }

    /**
     * @param WithdrawPerformed $withdrawPerformed
     * @noinspection PhpUnused
     */
    public function onWithdrawPerformed(WithdrawPerformed $withdrawPerformed): void
    {
        $this->document = $withdrawPerformed->getAccountId();
        $newBalance = $this->balance->getAmount() - $withdrawPerformed->getFinancialTransaction()->getAmount()->getValue();
        $this->balance = new Balance($newBalance);
    }

    /**
     * @param Amount $value
     * @return bool
     */
    private function withoutBalance(Amount $value): bool
    {
        if ($this->balance->getAmount() < $value->getValue()) {
            return true;
        }

        return false;
    }

    /**
     * @param Deposit $deposit
     */
    public function deposit(Deposit $deposit): void
    {
        if ($deposit->getAmount()->getValue() > self::DEPOSIT_LIMIT) {
            throw new DomainException('Limite por depósito atingido. Valor máximo permitido é de R$10.000,00');
        }

        //$this->financialTransaction = new FinancialTransaction(new DateTimeImmutable(), $deposit->getAmount());
        $this->balance = new Balance($this->balance->getAmount() + $deposit->getAmount()->getValue());
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
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
     * @return string
     */
    protected function getIdentityProperty(): string
    {
        return self::IDENTITY;
    }
}