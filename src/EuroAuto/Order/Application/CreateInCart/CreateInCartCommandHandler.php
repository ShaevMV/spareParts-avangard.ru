<?php

namespace ApiFacade\EuroAuto\Order\Application\CreateInCart;

use ApiFacade\EuroAuto\Connect\EuroAutoOrderApi;
use ApiFacade\EuroAuto\Order\Domain\Order;
use ApiFacade\EuroAuto\Order\Repositories\OrderRepositoryInterface;
use ApiFacade\Shared\Domain\Bus\Command\CommandHandler;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Order\ExceptionOrder;
use Nette\Utils\JsonException;

class CreateInCartCommandHandler implements CommandHandler
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private EuroAutoOrderApi $autoOrderApi,
    ) {
    }

    /**
     * @throws ExceptionOrder
     * @throws JsonException
     * @throws ExceptionConnectApi
     */
    public function __invoke(CreateInCartCommand $command): void
    {
        $orderInfoDto = $this->autoOrderApi->createOrderByCartId($command->getCartId(), $command->getBecome());

        $this->orderRepository->setOrderEntity(Order::fromOrderInfoDto($orderInfoDto, $command->getCart()));
    }
}
