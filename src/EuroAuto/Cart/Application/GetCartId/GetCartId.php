<?php

namespace ApiFacade\EuroAuto\Cart\Application\GetCartId;

use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GetCartId
{
    private InMemorySymfonyQueryBus $queryBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->queryBus = new InMemorySymfonyQueryBus([
            app()->get(GetCartIdQueryHandler::class)
        ]);
    }

    public function getId(string $become): ?Response
    {
        return $this->queryBus->ask(new GetCartIdQuery($become));
    }
}
