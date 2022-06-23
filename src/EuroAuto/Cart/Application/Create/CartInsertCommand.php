<?php

namespace ApiFacade\EuroAuto\Cart\Application\Create;

use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\Shared\Domain\Bus\Command\Command;

final class CartInsertCommand implements Command
{
    public function __construct(
        private ArticularRawDto $particular,
        private string $become,
    ) {
    }

    public function getParticular(): ArticularRawDto
    {
        return $this->particular;
    }

    public function getBecome(): string
    {
        return $this->become;
    }
}
