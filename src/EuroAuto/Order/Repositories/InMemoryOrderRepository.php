<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order\Repositories;

use ApiFacade\EuroAuto\Helpers\EuroAutoRedisHelper;
use ApiFacade\EuroAuto\Order\Domain\Order;
use Exception;
use Illuminate\Support\Facades\Redis;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private Redis $redis,
    ) {
    }

    /**
     * @return Order[]
     * @throws JsonException
     */
    public function getList(): array
    {
        $keys = $this->redis::keys(EuroAutoRedisHelper::getOrderKey('*'));
        $result = [];
        foreach ($keys as $key) {
            $temp = $this->getOrderEntity($key);
            if (!is_null($temp)) {
                $result[$temp->getId()] = $temp;
            }
        }

        return $result;
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public function getOrderEntity(string $key): ?Order
    {
        $jsonOrder = $this->redis::get($key);
        if (!$jsonOrder) {
            return null;
        }

        return Order::fromState((array) Json::decode($jsonOrder, 1));
    }

    /**
     * @throws JsonException
     */
    public function setOrderEntity(Order $orderEntity): bool
    {
        $key = EuroAutoRedisHelper::getOrderKey($orderEntity->getBecome());

        return $this->redis::set($key, $orderEntity->toJson());
    }

    public function remove(string $become): void
    {
        $key = EuroAutoRedisHelper::getOrderKey($become);
        $this->redis::del($key);
    }

    public function removeAll(): void
    {
        $keys = $this->redis::keys(EuroAutoRedisHelper::getOrderKey('*'));
        foreach ($keys as $key) {
            $this->redis::del($key);
        }
    }
}
