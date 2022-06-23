<?php

namespace ApiFacade\EuroAuto\Cart\Application\InsertProductInCart;

use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Infrastructure\Bus\Command\InMemorySymfonyCommandBus;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;

class InsertProductInCart
{
    private InMemorySymfonyCommandBus $commandBus;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct()
    {
        $this->commandBus = new InMemorySymfonyCommandBus([
            app()->get(InsertProductInCartCommandHandler::class)
        ]);
    }

    /**
     * @throws Throwable
     */
    public function add(OffersDto $offersDto, string $become): void
    {
        $this->commandBus->dispatch(new InsertProductInCartCommand(
            $offersDto->getId(),
            $offersDto->getQuantity(),
            $offersDto->getPrice(),
            $become
        ));
    }
}
