<?php

namespace Banking\Account\Command\Deposit;

use Banking\Account\Model\AccountRepository;
use Exception;

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
     * @throws Exception
     */
    public function __invoke(Deposit $deposit)
    {
        $account = $this->accountRepository->pull($deposit->getDocument());
        $account->deposit($deposit);
        $this->accountRepository->push($account);
    }
}