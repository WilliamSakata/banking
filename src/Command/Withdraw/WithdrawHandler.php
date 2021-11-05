<?php

namespace Banking\Account\Command\Withdraw;

use Banking\Account\Model\AccountRepository;
use Exception;

class WithdrawHandler
{
    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    /**
     * @param Withdraw $withdraw
     * @throws Exception
     */
    public function __invoke(Withdraw $withdraw)
    {
        $account = $this->accountRepository->pull($withdraw->getDocument());
        $account->withDraw($withdraw);
        $this->accountRepository->push($account);
    }
}