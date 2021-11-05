<?php

namespace Banking\Account\Driven\Persistence;

use Banking\Account\Model\Account;
use Banking\Account\Model\AccountRepository as Repository;
use Banking\Account\Model\Cpf;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;

class AccountRepository implements Repository
{
    /**
     * @param MySqlAdapter $adapter
     */
    public function __construct(private MySqlAdapter $adapter)
    {
    }

    /**
     * @param Cpf $cpf
     * @return Account
     * @throws DriverException
     * @throws Exception
     */
    public function pull(Cpf $cpf): Account
    {
        $builder = $this->adapter->createQueryBuilder();

        $statement = $builder->select('*')
            ->from('accounts')
            ->where('accountId = :accountId')
            ->setParameter(':accountId', $cpf->getValue())
            ->execute();

        $result = $statement->fetchAssociative();
        return Account::reconstitute($cpf, $result);
    }

    /**
     * @param Account $account
     * @throws Exception
     */
    public function push(Account $account): void
    {
        try {
            $this->adapter->beginTransaction();

            $builder = $this->adapter->createQueryBuilder();

            $builder->update('accounts')
                ->set('accountId', ':accountId')
                ->set('balance', ':amount')
                ->where('accountId = :accountId')
                ->setParameter(':accountId', $account->getDocument()->getValue())
                ->setParameter(':amount', $account->getBalance()->getValue())
                ->execute();

            $qb = $this->adapter->createQueryBuilder();

            $qb->insert('financialTransactions')
                ->setValue('accountId', ':accountId')
                ->setValue('amount', ':amount')
                ->setValue('occurredOn', ':occurredOn')
                ->setParameter(':accountId', $account->getDocument()->getValue())
                ->setParameter(':amount', $account->getFinancialTransaction()->getTransactionValue()->getValue())
                ->setParameter(':occurredOn', $account->getFinancialTransaction()->getDate()->format('Y-m-d h:m:s'))
                ->execute();

            $this->adapter->commit();
        } catch (Exception $ex) {
            $this->adapter->rollback();
            throw $ex;
        }
    }
}