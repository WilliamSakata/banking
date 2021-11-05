<?php

namespace Banking\Account\Driver\Action;

use Banking\Account\Command\Create\Create as CreateUseCase;
use Banking\Account\Command\Create\CreateHandler;
use Banking\Account\Model\Cpf;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Create
{
    private const NO_CONTENT = 204;

    /**
     * @param CreateHandler $handler
     */
    public function __construct(private CreateHandler $handler)
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

        $create = new CreateUseCase(
            new Cpf($payload['cpf'])
        );

        $this->handler->__invoke($create);

        return $response->withStatus(self::NO_CONTENT);
    }
}