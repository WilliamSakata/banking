<?php

namespace Banking\Account\Driver\Http\Action;

use Banking\Account\Command\Withdraw\Withdraw as WithdrawUseCase;
use Banking\Account\Command\Withdraw\WithdrawHandler;
use Banking\Account\Model\Amount;
use Banking\Account\Model\Cpf;
use Banking\Account\Model\Currency;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Withdraw
{
    private const NO_CONTENT = 204;

    /**
     * @param WithdrawHandler $handler
     */
    public function __construct(private WithdrawHandler $handler)
    {
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request, Response $response): Response
    {
        $payload = $request->getParsedBody();

        $withdraw = new WithdrawUseCase(
            new Cpf($payload['cpf']),
            new Amount($payload['amount'], new Currency('BRL'))
        );

        $this->handler->__invoke($withdraw);

        return $response->withStatus(self::NO_CONTENT);
    }
}
