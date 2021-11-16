<?php

namespace Banking\Account\Query\Balance;

use Banking\Account\Driven\Database\AccountDao;
use Banking\Account\Model\Amount;
use Banking\Account\Model\Cpf;
use Banking\Account\Model\Currency;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;

class BalanceHandler
{
    /**
     * @param AccountDao $accountDao
     */
    public function __construct(private AccountDao $accountDao)
    {
    }

    /**
     * @param string $cpf
     * @return BalanceResult
     * @throws DriverException
     * @throws Exception
     */
    public function __invoke(string $cpf): BalanceResult
    {
        $balanceUseCase = new Balance(new Cpf($cpf));

        $result = $this->accountDao->findAccountBalanceById($balanceUseCase);

        return new BalanceResult(new Cpf($result['accountId']), new Amount($result['balance'], new Currency('BRL')));
    }
}