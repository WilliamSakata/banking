<?php

namespace Banking\Account\Model;

use Banking\Account\Model\BuildingBlocks\Identity;
use Banking\Account\Model\BuildingBlocks\ValueObject\SingleValueObjectCapabilities;
use Banking\Account\Model\errors\InvalidDocument;

class Cpf implements Identity
{
    use SingleValueObjectCapabilities;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!$this->isValid($value)) {
            throw new InvalidDocument();
        }

        $this->value = $value;
    }

    /**
     * @param  string $value
     * @return bool
     */
    private function isValid(string $value): bool
    {
        $numberToLoop = [9, 10];

        $cpf = preg_replace('/[^0-9]/is', '', $value);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        foreach ($numberToLoop as $item) {
            $sum = 0;
            $numberToMultiply = $item + 1;

            for ($cpfIndex = 0; $cpfIndex < $item; $cpfIndex++) {
                $sum += $cpf[$cpfIndex] * ($numberToMultiply--);
            }

            $result = (($sum * 10) % 11);

            if ($cpf[$item] != $result) {
                return false;
            }
        }

        return true;
    }

    public function toArray(): array
    {
        return [
            'cpf' => $this->getValue()
        ];
    }
}
