<?php

namespace Banking\Account\Driver\Action;

use Banking\Account\Command\Deposit\Deposit as DepositUseCase;
use Banking\Account\Command\Deposit\DepositHandler;
use Banking\Account\Model\Amount;
use Banking\Account\Model\Currency;
use Banking\Account\Model\Cpf;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class Deposit
{
    private const NO_CONTENT = 204;

    /**
     * @param DepositHandler $handler
     */
    public function __construct(private DepositHandler $handler)
    {
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();

        $deposit = new DepositUseCase(
            new Cpf($payload['cpf']),
            new Amount($payload['amount'], new Currency('BRL'))
        );

        $this->handler->__invoke($deposit);

        return $response->withStatus(self::NO_CONTENT);
    }
}