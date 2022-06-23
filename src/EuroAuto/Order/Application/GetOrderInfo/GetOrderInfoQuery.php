<?php

namespace ApiFacade\EuroAuto\Order\Application\GetOrderInfo;

use ApiFacade\Shared\Domain\Bus\Query\Query;

class GetOrderInfoQuery implements Query
{
    public function __construct(
        private string $become
    ){
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
