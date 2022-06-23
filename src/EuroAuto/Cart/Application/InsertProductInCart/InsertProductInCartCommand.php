<?php

namespace ApiFacade\EuroAuto\Cart\Application\InsertProductInCart;

use ApiFacade\Shared\Domain\Bus\Command\Command;

class InsertProductInCartCommand implements Command
{
    public function __construct(
        protected string $id,
        protected int $quantity,
        protected int $price,
        protected string $become,
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
