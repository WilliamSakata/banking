<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\InvalidFinancialTransactionType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class DepositPerformedTest extends TestCase
{
    private const CPF = '085.792.800-79';
    private const CURRENCY = 'BRL';

    private DateTimeImmutable $occurredOn;

    public function setUp(): void
    {
        $this->document = new Cpf(self::CPF);
        $this->currency = new Currency(self::CURRENCY);
        $this->occurredOn = new DateTimeImmutable();
    }

    public function testDepositPerformedEvent(): void
    {
        $financialTransaction = new FinancialTransaction(
            new Amount(
                100,
                new Currency(self::CURRENCY)
            ),
            $this->occurredOn,
            FinancialTransactionType::CREDIT
        );

        $actual = new DepositPerformed(
            new Cpf(self::CPF),
            $financialTransaction
        );

        static::assertEquals(self::CPF, $actual->getAccountId()->getValue());
        static::assertEquals($this->financialTransactionArray(), $actual->getFinancialTransaction()->toArray());
    }

    public function testInvalidFinancialTransaction(): void
    {
        $this->expectException(InvalidFinancialTransactionType::class);
        $this->expectErrorMessage('Invalid financial transaction type D for DepositPerformed event');

        $financialTransaction = new FinancialTransaction(
            new Amount(10, new Currency('BRL')),
            $this->occurredOn,
            FinancialTransactionType::DEBIT
        );

        new DepositPerformed(new Cpf(self::CPF), $financialTransaction);
    }

    private function financialTransactionArray(): array
    {
        return [
            'amount' => [
                'value' => 100,
                'currency' => 'BRL'
            ],
            'type' => 'C',
            'createdAt' => $this->occurredOn->format('Y-m-d H:i:s')
        ];
    }
}
