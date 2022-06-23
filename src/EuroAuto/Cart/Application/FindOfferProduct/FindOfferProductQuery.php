<?php

namespace ApiFacade\EuroAuto\Cart\Application\FindOfferProduct;

use ApiFacade\Shared\Domain\Bus\Query\Query;

class FindOfferProductQuery implements Query
{
    public function __construct(
        private int $manufacturerId,
        private string $code,
    ) {
    }

    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
