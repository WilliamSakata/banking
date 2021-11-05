<?php

namespace Banking\Account\Driver\Action;

use Banking\Account\Query\AccountStatement\AccountStatementHandler;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AccountStatement
{

    /**
     * @param AccountStatementHandler $handler
     */
    public function __construct(private AccountStatementHandler $handler)
    {
    }

    /**
     * @throws Exception
     * @throws DriverException
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $accountStatement = $this->handler->__invoke($request->getQueryParams()['id']);

        $response
            ->getBody()
            ->write(json_encode($accountStatement));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}