<?php

namespace Banking\Account\Model;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class AccountCreatedTest extends TestCase
{
    private const CURRENCY = 'BRL';
    private const CPF = '085.792.800-79';

    private Cpf $document;
    private Currency $currency;
    private DateTimeImmutable $occurredOn;

    public function setUp(): void
    {
        $this->document = new Cpf(self::CPF);
        $this->currency = new Currency(self::CURRENCY);
        $this->occurredOn = new DateTimeImmutable();
    }

    public function testCreateSuccess()
    {
        $document = $this->document;
        $amount = new Amount(100, $this->currency);

        $actual = new AccountCreated($document, $amount, $this->occurredOn);

        static::assertEquals(1, $actual->getRevision());
        static::assertEquals($this->buildArray(), $actual->toArray());
        static::assertEquals($this->occurredOn, $actual->getOccurredOn());
        static::assertEquals('Account', $actual->getAggregateType());
        static::assertEquals('100', $actual->getAmount()->getValue());
        static::assertEquals('085.792.800-79', $actual->getAccountId()->getValue());
        static::assertEquals('BRL', $actual->getAmount()->getCurrency()->getValue());
    }

    private function buildArray(): array
    {
        return [
            'accountId' => [
                'cpf' => self::CPF
            ],
            'amount' => [
                'value' => 100,
                'currency' => 'BRL'
            ],
            'occurredOn' => $this->occurredOn->format('Y-m-d H:i:s')
        ];
    }
}
