<?php

namespace ApiFacade\EuroAuto\Cart\Application\GetCartId;

use ApiFacade\Shared\Domain\Bus\Query\Query;

class GetCartIdQuery implements Query
{
    public function __construct(
        private string $become
    ) {
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
