<?php

namespace Banking\Account\Query\AccountStatement;

use Banking\Account\Driven\Database\FinancialTransactionDao;
use Banking\Account\Model\Cpf;
use Doctrine\DBAL\Driver as DriverException;
use Doctrine\DBAL\Exception;

class AccountStatementHandler
{
    /**
     * @param FinancialTransactionDao $financialTransactionDao
     */
    public function __construct(private FinancialTransactionDao $financialTransactionDao)
    {
    }

    /**
     * @throws DriverException
     * @throws Exception
     * @throws DriverException\Exception
     * @throws \Exception
     */
    public function __invoke(string $cpf): array
    {
        $useCase = new AccountStatement(new Cpf($cpf));

        return $this->financialTransactionDao->findAccountStatements($useCase);
    }
}