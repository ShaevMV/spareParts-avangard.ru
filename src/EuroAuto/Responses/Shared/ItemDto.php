<?php

namespace ApiFacade\EuroAuto\Responses\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ItemDto extends AbstractionEntity
{
    public function __construct(
        protected string $id,
        protected int $quantity,
        protected int $price,
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['quantity'],
            $data['price']
        );
    }
}
