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
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function __invoke(Create $create)
    {
        if ($this->accountExists($create)) {
            return;
        }

        $account = Account::blank($create->getDocument());

        $account->create();
        $this->accountRepository->push($account);
    }

    /**
     * @param Create $create
     * @return bool
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function accountExists(Create $create): bool
    {
        return $this->accountRepository->accountExists($create->getDocument());
    }
}