<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Cart\Application\FindOfferProduct;

use ApiFacade\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use Assert\Assertion;
use Assert\AssertionFailedException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FindOfferProduct
{
    private InMemorySymfonyQueryBus $queryBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $get = app()->get(FindOfferProductQueryHandler::class);

        $this->queryBus = new InMemorySymfonyQueryBus([
            FindOfferProductQuery::class => $get,
        ]);
    }

    public function find(
        int $manufacturerId,
        string $code
    ): ?ResponseOffersList {
        /** @var ?ResponseOffersList $result */
        $result = $this->queryBus->ask(new FindOfferProductQuery(
            $manufacturerId, $code
        ));

        return $result;
    }
}
