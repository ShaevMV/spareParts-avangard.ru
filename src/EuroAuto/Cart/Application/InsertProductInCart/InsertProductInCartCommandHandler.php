<?php

namespace ApiFacade\EuroAuto\Cart\Application\InsertProductInCart;

use ApiFacade\EuroAuto\Cart\Repositories\CartRepositoryInterface;
use ApiFacade\EuroAuto\Connect\EuroAutoCartApi;
use ApiFacade\Shared\Domain\Bus\Command\CommandHandler;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use Nette\Utils\JsonException;
use RuntimeException;

class InsertProductInCartCommandHandler implements CommandHandler
{
    public function __construct(
        private EuroAutoCartApi $cartApi,
        private CartRepositoryInterface $cartRepository,
    ) {
    }

    /**
     * @throws JsonException
     * @throws ExceptionConnectApi
     */
    public function __invoke(InsertProductInCartCommand $command): void
    {
        $cartInfoDto = $this->cartApi->createOrAddCart(
            $command->getId(),
            $command->getQuantity(),
            $command->getPrice(),
            $command->getBecome()
        );

        if (!$this->cartRepository->setCartId($cartInfoDto->getCart(), $command->getBecome())) {
            throw new RuntimeException('Не получилось записать корзину '.$cartInfoDto->getCart()->toJson());
        }
    }
}
