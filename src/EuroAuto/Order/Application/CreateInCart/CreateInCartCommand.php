<?php

namespace ApiFacade\EuroAuto\Order\Application\CreateInCart;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\Shared\Domain\Bus\Command\Command;

class CreateInCartCommand implements Command
{
    public function __construct(
        private Cart $cart
    ) {
    }

    public function getBecome(): string
    {
        return $this->cart->getBecome();
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }

    public function getCartId(): string
    {
        return $this->cart->getId();
    }
}
