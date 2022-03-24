<?php

namespace Banking\Account\Model;

use Banking\Account\Model\errors\InvalidDocument;
use PHPUnit\Framework\TestCase;

class CpfTest extends TestCase
{
    private const VALID_CPF = '232.107.840-51';
    private const INVALID_CPF = '123.456.789-00';

    public function testCreateSuccess():void {
        $cpf = new Cpf(self::VALID_CPF);
        static::assertInstanceOf(Cpf::class, $cpf);
    }

    public function testCreateFail():void {
        static::expectException(InvalidDocument::class);
        new Cpf(self::INVALID_CPF);
    }
}
