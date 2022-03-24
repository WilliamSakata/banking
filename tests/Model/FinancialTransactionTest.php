<?php

namespace Banking\Account\Model;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class FinancialTransactionTest extends TestCase
{
    public function testCreateFinancialTransaction(): void
    {
        $date = new DateTimeImmutable();
        $type = FinancialTransactionType::CREDIT;
        $amount = new Amount(10, new Currency("BRL"));

        $actual = new FinancialTransaction($amount, $date, $type);

        static::assertEquals($amount, $actual->getAmount());
        static::assertEquals($date, $actual->getCreatedAt());
        static::assertEquals(FinancialTransactionType::CREDIT, $actual->getType());
    }

    public function testToArray(): void
    {
        $date = new DateTimeImmutable('2022-03-01 12:00:00');
        $type = FinancialTransactionType::CREDIT;
        $amount = new Amount(10, new Currency("BRL"));

        $actual = new FinancialTransaction($amount, $date, $type);

        static::assertEquals($this->expectedArray(), $actual->toArray());
    }

    private function expectedArray(): array
    {
        return [
            'amount' => [
                'value' => 10,
                'currency' => 'BRL'
            ],
            'type' => 'C',
            'createdAt' => '2022-03-01 12:00:00'
        ];
    }
}
