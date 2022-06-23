<?php

namespace ApiFacade\EuroAuto\Order\Application\Create;

use ApiFacade\EuroAuto\Order\Application\CreateInCart\CreateInCart;
use ApiFacade\EuroAuto\Order\Application\CreateInCart\CreateInCartCommand;
use ApiFacade\EuroAuto\Order\Application\GetOrderInfo\GetOrderInfo;
use ApiFacade\EuroAuto\Order\Domain\Order;
use ApiFacade\EuroAuto\Order\Domain\OrderCreatingDomainEvent;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Order\ExceptionOrder;
use Nette\Utils\JsonException;
use Throwable;

class OrderCreate
{
    public function __construct(
        private CreateInCart $createInCart,
        private GetOrderInfo $getOrderInfo,
    ) {
    }


    /**
     * @throws Throwable
     * @throws JsonException
     * @throws ExceptionOrder
     * @throws ExceptionConnectApi
     */
    public function create(OrderCreatingDomainEvent $event): Order
    {
        $this->createInCart->create(new CreateInCartCommand($event->getCart()));

        return $this->getOrderInfo->get($event->getBecome());
    }
}
