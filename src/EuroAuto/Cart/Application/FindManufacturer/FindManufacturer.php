<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Cart\Application\FindManufacturer;

use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FindManufacturer
{
    private InMemorySymfonyQueryBus $queryBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->queryBus = new InMemorySymfonyQueryBus([
            FindManufacturerQuery::class => app()->get(FindManufacturerQueryHandler::class),
        ]);
    }

    public function find(ArticularRawDto $articularRawDto): ?ManufacturerResponse
    {
        /** @var ManufacturerResponse|null $result */
        $result = $this->queryBus->ask(new FindManufacturerQuery(
            $articularRawDto->articular,
            $articularRawDto->manufacture
        ));

        return $result;
    }
}
