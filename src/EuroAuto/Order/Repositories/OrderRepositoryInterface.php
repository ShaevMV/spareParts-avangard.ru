<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order\Repositories;

use ApiFacade\EuroAuto\Order\Domain\Order;

interface OrderRepositoryInterface
{
    /** Записать Order в память */
    public function setOrderEntity(Order $orderEntity): bool;

    public function getOrderEntity(string $key): ?Order;

    /** @return Order[] */
    public function getList(): array;

    public function remove(string $become): void;

    public function removeAll(): void;
}
