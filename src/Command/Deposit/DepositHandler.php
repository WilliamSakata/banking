<?php

namespace Banking\Account\Command\Deposit;

use Banking\Account\Model\AccountRepository;

class DepositHandler
{
    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    /**
     * @param Deposit $deposit
     */
    public function __invoke(Deposit $deposit)
    {
        $account = $this->accountRepository->pull($deposit->getDocument());
        $account->deposit($deposit);
        $this->accountRepository->push($account);
    }
}