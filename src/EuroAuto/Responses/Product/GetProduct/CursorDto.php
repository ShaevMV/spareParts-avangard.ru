<?php

namespace ApiFacade\EuroAuto\Responses\Product\GetProduct;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class CursorDto extends AbstractionEntity
{
    public function __construct(
        protected ?int $from,
        protected ?int $limit
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['from'],
            $data['limit']
        );
    }
}
