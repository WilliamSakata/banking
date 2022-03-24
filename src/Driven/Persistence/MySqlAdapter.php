<?php

namespace Banking\Account\Driven\Persistence;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Driver\ResultStatement;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Query\QueryBuilder;

class MySqlAdapter
{
    /**
     * @param Connection $connection
     */
    public function __construct(private Connection $connection)
    {
    }

    /**
     * @param  string $sql
     * @param  array  $params
     * @param  array  $types
     * @return int
     * @throws Exception
     */
    public function execute(string $sql, array $params = [], array $types = []): int
    {
        return $this->connection->executeStatement($sql, $params, $types);
    }

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    public function beginTransaction(): void
    {
        $this->connection->beginTransaction();
    }

    /**
     * @throws ConnectionException
     */
    public function commit(): void
    {
        $this->connection->commit();
    }

    /**
     * @throws ConnectionException
     */
    public function rollback(): void
    {
        $this->connection->rollBack();
    }
}
