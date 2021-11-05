<?php

namespace Banking\Account\Model;

interface Identity
{
    public function getValue(): mixed;
}