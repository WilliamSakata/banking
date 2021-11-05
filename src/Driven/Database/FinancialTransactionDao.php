<?php

namespace Banking\Account\Driven\Database;

use Banking\Account\Query\AccountStatement\AccountStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Driver as DriverException;

class FinancialTransactionDao
{
    /**
     * @param MySqlAdapter $adapter
     */
    public function __construct(private MySqlAdapter $adapter)
    {
    }

    /**
     * @throws Exception
     * @throws DriverException|DriverException\Exception
     */
    public function findAccountStatements(AccountStatement $accountStatement): array
    {
        $builder = $this->adapter->createQueryBuilder();

        $statement = $builder->select('*')
            ->from('financialTransactions')
            ->where('accountId = :accountId')
            ->setParameter(':accountId', $accountStatement->getDocument()->getValue())
            ->execute();

        return $statement->fetchAllAssociative();
    }
}