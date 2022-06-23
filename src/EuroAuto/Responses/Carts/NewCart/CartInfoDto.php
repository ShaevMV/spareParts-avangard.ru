<?php

namespace ApiFacade\EuroAuto\Responses\Carts\NewCart;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CartInfoDto extends AbstractionEntity
{
    public function __construct(
        protected CartDto $cart,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            CartDto::fromState($data['cart'])
        );
    }

    public function getId(): string
    {
        return $this->cart->getId();
    }

    public function getCart(): CartDto
    {
        return $this->cart;
    }
}
