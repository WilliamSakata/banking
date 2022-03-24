<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\ValueObject\ImmutableCapabilities;
use Banking\Account\Model\BuildingBlocks\ValueObject\ValueObject;
use DateTimeImmutable;

final class FinancialTransaction implements ValueObject
{
    use ImmutableCapabilities;

    /**
     * @param DateTimeImmutable        $createdAt
     * @param Amount                   $amount
     * @param FinancialTransactionType $type
     */
    public function __construct(
        private Amount $amount,
        private DateTimeImmutable $createdAt,
        private FinancialTransactionType $type
    ) {
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
     * @return FinancialTransactionType
     */
    public function getType(): FinancialTransactionType
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
            'type' => $this->type->value,
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s')
        ];
    }
}
