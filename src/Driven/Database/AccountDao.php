<?php

namespace Banking\Account\Driven\Database;

use Banking\Account\Query\Balance\Balance;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception as DbalException;

class AccountDao
{
    /**
     * @param MySqlAdapter $adapter
     */
    public function __construct(private MySqlAdapter $adapter)
    {
    }

    /**
     * @param Balance $balanceUseCase
     * @return array
     * @throws DbalException
     * @throws DriverException
     */
    public function findAccountBalanceById(Balance $balanceUseCase): array
    {
        $builder = $this->adapter->createQueryBuilder();

        $statement = $builder->select(['accountId', 'balance'])
            ->from('accounts')
            ->where('accountId = :accountId')
            ->setParameter(':accountId', $balanceUseCase->getDocument()->getValue())
            ->execute();

        return $statement->fetchAssociative();
    }
}