<?php

namespace ApiFacade\EuroAuto\Responses\Carts\GetCart;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;
class CartItemsDto extends AbstractionEntity
{
    public function __construct(
        protected int $number,
        protected int $price,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['number'],
            $data['price']
        );
    }
}
