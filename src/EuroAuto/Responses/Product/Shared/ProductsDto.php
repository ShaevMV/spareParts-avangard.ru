<?php

namespace ApiFacade\EuroAuto\Responses\Product\Shared;

use ApiFacade\EuroAuto\Responses\Product\GetProduct\AttributesDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ProductsDto extends AbstractionEntity
{
    public function __construct(
        protected ProductNameDto $name,
        protected string $condition,
        protected string $comment,
        protected string $manufacturer_code,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            ProductNameDto::fromState($data['name']),
            $data['condition'],
            $data['comment'],
            $data['manufacturer_code'],
        );
    }

    public function getCondition(): string
    {
        return $this->condition;
    }
}
