<?php

namespace ApiFacade\EuroAuto\Responses\Product\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ManufacturerCountryDto extends AbstractionEntity
{
    public function __construct(
        protected int $id,
        protected string $name
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['name']
        );
    }
}
