<?php

namespace Banking\Account\Model;

interface AccountRepository
{
    /**
     * @param  Cpf $cpf
     * @return Account
     */
    public function pull(Cpf $cpf): Account;

    /**
     * @param Account $account
     */
    public function push(Account $account): void;
}
