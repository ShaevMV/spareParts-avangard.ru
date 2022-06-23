<?php

namespace ApiFacade\EuroAuto\Order\Application\CreateInCart;

use ApiFacade\Shared\Infrastructure\Bus\Command\InMemorySymfonyCommandBus;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Order\ExceptionOrder;
use Nette\Utils\JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class CreateInCart
{
    private InMemorySymfonyCommandBus $commandBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->commandBus = new InMemorySymfonyCommandBus([
            app()->get(CreateInCartCommandHandler::class)
        ]);
    }

    /**
     * @throws Throwable
     * @throws ExceptionOrder
     * @throws JsonException
     * @throws ExceptionConnectApi
     */
    public function create(CreateInCartCommand $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
