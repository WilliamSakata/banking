<?php

namespace Banking\Account\Command\Create;

use Banking\Account\Model\Cpf;

class Create
{
    /**
     * @param Cpf $document
     */
    public function __construct(private Cpf $document)
    {
    }

    /**
     * @return Cpf
     */
    public function getDocument(): Cpf
    {
        return $this->document;
    }
}
