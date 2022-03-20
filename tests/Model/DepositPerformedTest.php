<?php

namespace Banking\Account\Model;

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

    public function testDepositPerformedEvent()
    {
        $financialTransaction = new FinancialTransaction(
            $this->occurredOn,
            new Amount(
                100,
                new Currency(self::CURRENCY)
            ),
            FinancialTransactionType::CREDIT
        );

        $actual = new DepositPerformed(
            new Cpf(self::CPF),
            $financialTransaction
        );

        static::assertEquals(self::CPF, $actual->getAccountId()->getValue());
        static::assertEquals($this->financialTransactionArray(), $actual->getFinancialTransaction()->toArray());
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
