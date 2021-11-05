<?php

namespace Banking\Account\Model;

interface AccountRepository
{
    public function pull(Cpf $cpf): Account;
    public function push(Account $account): void;
}