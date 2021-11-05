<?php

namespace Banking\Account\Driver\Action;

use Banking\Account\Query\Balance\BalanceHandler;
use Doctrine\DBAL\Driver\Exception as DriverException;
use Doctrine\DBAL\Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Balance
{
    /**
     * @param BalanceHandler $handler
     */
    public function __construct(private BalanceHandler $handler)
    {
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws DriverException
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $account = $this->handler->__invoke($request->getQueryParams()['id']);

        $response
            ->getBody()
            ->write(json_encode($account->toArray()));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}