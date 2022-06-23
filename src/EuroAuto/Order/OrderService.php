<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order;

use ApiFacade\EuroAuto\Order\Domain\Order;
use ApiFacade\EuroAuto\Responses\Order\OrderListDto;

class OrderService
{

    /**
     * @param  OrderListDto  $orderListDto
     * @param  Order[]  $orderListInMemory
     * @return Order[]
     */
    public function updateStatus(OrderListDto $orderListDto, array $orderListInMemory): array
    {
        foreach ($orderListDto->getItems() as $orderItemDto) {
            if(isset($orderListInMemory[$orderItemDto->getOrderId()])) {
                $orderListInMemory[$orderItemDto->getOrderId()]->setState(
                    $orderItemDto->getState()
                );
            }
        }

        return $orderListInMemory;
    }
}
