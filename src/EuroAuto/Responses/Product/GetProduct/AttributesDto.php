<?php

namespace ApiFacade\EuroAuto\Responses\Product\GetProduct;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class AttributesDto extends AbstractionEntity
{
    public function __construct(
        protected float $weight,
        protected ?int $wearout = null
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['weight'],
            $data['wearout'] ?? null
        );
    }
}
