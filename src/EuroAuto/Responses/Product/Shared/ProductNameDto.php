<?php

namespace ApiFacade\EuroAuto\Responses\Product\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ProductNameDto extends AbstractionEntity
{
    public function __construct(
        protected string $id,
        protected string $name,
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['name']
        );
    }
}
