<?php

namespace ApiFacade\EuroAuto\Cart\Application\FindManufacturer;

use ApiFacade\Shared\Domain\Bus\Query\Query;

final class FindManufacturerQuery implements Query
{
    public function __construct(
        private string $code,
        private string $manufacturerName,
    ){
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getManufacturerName(): string
    {
        return $this->manufacturerName;
    }
}
