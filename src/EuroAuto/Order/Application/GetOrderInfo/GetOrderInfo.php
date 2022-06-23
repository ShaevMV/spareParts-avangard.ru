<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order\Application\GetOrderInfo;

use ApiFacade\EuroAuto\Order\Domain\Order;
use ApiFacade\Shared\Infrastructure\Bus\Query\InMemorySymfonyQueryBus;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class GetOrderInfo
{
    private InMemorySymfonyQueryBus $queryBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->queryBus = new InMemorySymfonyQueryBus([
            app()->get(GetOrderInfoQueryHandler::class),
        ]);
    }

    public function get(string $become): Order
    {
        /** @var ?Order $result */
        $result = $this->queryBus->ask(new GetOrderInfoQuery($become));

        if (is_null($result)) {
            throw new RuntimeException('Не найден заказ '.$become);
        }

        return $result;
    }
}
