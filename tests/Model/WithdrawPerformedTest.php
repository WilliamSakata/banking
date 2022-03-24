<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\InvalidFinancialTransactionType;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class WithdrawPerformedTest extends TestCase
{
    private const CPF = '368.734.550-59';
    private const DATE = '2022-03-01 12:00:00';

    public function testWithdrawalCreate(): void
    {
        $date = new DateTimeImmutable(self::DATE);
        $document = new Cpf(self::CPF);
        $amount = new Amount(10, new Currency('BRL'));
        $financialTransaction = new FinancialTransaction($amount, $date,FinancialTransactionType::DEBIT);

        $withdrawPerformed = new WithdrawPerformed($document, $financialTransaction);

        static::assertEquals(1, $withdrawPerformed->getRevision());
        static::assertEquals($this->expectedArray(), $withdrawPerformed->toArray());
        static::assertEquals('Account', $withdrawPerformed->getAggregateType());
        static::assertEquals('368.734.550-59', $withdrawPerformed->getAccountId());
        static::assertEquals($financialTransaction, $withdrawPerformed->getFinancialTransaction());
    }

    public function testCreateWithInvalidFinancialTransactionType(): void
    {
        $this->expectException(InvalidFinancialTransactionType::class);
        $this->expectErrorMessage('Invalid financial transaction type C for WithdrawPerformed event');

        $date = new DateTimeImmutable(self::DATE);
        $document = new Cpf(self::CPF);
        $amount = new Amount(10, new Currency('BRL'));
        $financialTransaction = new FinancialTransaction($amount, $date, FinancialTransactionType::CREDIT);

        new WithdrawPerformed($document, $financialTransaction);
    }

    private function expectedArray(): array
    {
        return [
            'accountId' => [
                'cpf' => self::CPF
            ],
            'financialTransaction' => [
                'amount' => [
                    'value' => 10,
                    'currency' => 'BRL'
                ],
                'type' => 'D',
                'createdAt' => self::DATE
            ]
        ];
    }
}
