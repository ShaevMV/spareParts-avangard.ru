<?php

namespace ApiFacade\EuroAuto\Order\Application\GetOrderInfo;

use ApiFacade\EuroAuto\Helpers\EuroAutoRedisHelper;
use ApiFacade\EuroAuto\Order\Domain\Order;
use ApiFacade\EuroAuto\Order\Repositories\OrderRepositoryInterface;
use ApiFacade\EuroAuto\Responses\Order\OrderInfoDto;
use ApiFacade\Shared\Domain\Bus\Query\QueryHandler;

class GetOrderInfoQueryHandler implements QueryHandler
{
    public function __construct(
        private OrderRepositoryInterface $repository
    ){
    }

    public function __invoke(GetOrderInfoQuery $query): ?Order
    {
        return $this->repository->getOrderEntity(EuroAutoRedisHelper::getOrderKey($query->getBecome()));
    }
}
