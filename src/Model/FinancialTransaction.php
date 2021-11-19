<?php

namespace Banking\Account\Model;

use DateTimeImmutable;

final class FinancialTransaction
{
    /**
     * @param DateTimeImmutable $createdAt
     * @param Amount $amount
     * @param string $type
     */
    public function __construct(private DateTimeImmutable $createdAt, private Amount $amount, private string $type)
    {
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function toArray(): array
    {
        return [
            'amount' => [
                'value' => $this->amount->getValue(),
                'currency' => $this->amount->getCurrency()->getValue()
            ],
            'type' => $this->type,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}