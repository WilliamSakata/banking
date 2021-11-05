<?php

namespace Banking\Account\Model;

use Banking\Account\Command\Create\Create;
use Banking\Account\Command\Deposit\Deposit;
use Banking\Account\Command\Withdraw\Withdraw;
use Banking\Account\Model\ValueObject\Amount;
use Banking\Account\Model\ValueObject\Balance;
use Banking\Account\Model\ValueObject\Currency;
use DateTimeImmutable;
use DomainException;
use Exception;

final class Account implements EventSourcingRoot
{
    use EventSourcingCapabilities;

    private const DEPOSIT_LIMIT = 10000;

    /**
     * @var Cpf|null
     */
    private ?Cpf $document = null;

    /**
     * @var Balance|null
     */
    private ?Balance $balance = null;

    /**
     * @var DateTimeImmutable|null
     */
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var FinancialTransaction|null
     */
    private ?FinancialTransaction $financialTransaction = null;

    /**
     * @param Create $create
     * @throws Exception
     */
    public function create(Create $create): void
    {
        $event = new AccountCreated($create->getDocument(), new Amount(0.0, new Currency('BRL')), new DateTimeImmutable());

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
        $this->updatedAt = $accountCreated->getOccurredOn();
    }

    /**
     * @param Withdraw $withdraw
     * @throws Exception
     */
    public function withDraw(Withdraw $withdraw): void
    {
        if ($this->withoutBalance($withdraw->getAmount())) {
            throw new DomainException('Saldo insuficiente');
        }

        $amount = new Amount($withdraw->getAmount()->getValue() * -1, $withdraw->getAmount()->getCurrency());
        $this->financialTransaction = new FinancialTransaction(new DateTimeImmutable(), $amount);

        $balance = new Balance($this->balance->getAmount() - $withdraw->getAmount()->getValue());

        $event = new WithdrawPerformed($withdraw->getDocument(), new Amount($balance->getAmount(), $withdraw->getAmount()->getCurrency()), new DateTimeImmutable());
        $this->when($event, $this->identity);
    }

    /**
     * @param WithdrawPerformed $withdrawPerformed
     * @noinspection PhpUnused
     */
    public function onWithdrawPerformed(WithdrawPerformed $withdrawPerformed): void
    {
        $this->document = $withdrawPerformed->getAccountId();
        $this->balance = new Balance($withdrawPerformed->getAmount()->getValue());
        $this->updatedAt = $withdrawPerformed->getOccurredOn();
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
     * @return FinancialTransaction
     */
    public function getFinancialTransaction(): FinancialTransaction
    {
        return $this->financialTransaction;
    }
}