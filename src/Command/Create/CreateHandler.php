<?php

namespace Banking\Account\Command\Create;

use Banking\Account\Model\Account;
use Banking\Account\Model\AccountRepository;
use Exception;

class CreateHandler
{
    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(private AccountRepository $accountRepository)
    {
    }

    /**
     * @param Create $create
     * @throws Exception
     */
    public function __invoke(Create $create)
    {
        $account = Account::blank($create->getDocument());
        $account->create($create);
        $this->accountRepository->push($account);
    }
}