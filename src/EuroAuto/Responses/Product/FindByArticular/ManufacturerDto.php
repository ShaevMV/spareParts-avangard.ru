<?php

namespace ApiFacade\EuroAuto\Responses\Product\FindByArticular;

use ApiFacade\EuroAuto\Responses\Product\Shared\ManufacturerCountryDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ManufacturerDto extends AbstractionEntity
{
    public function __construct(
        protected int $id,
        protected string $name,
        protected ManufacturerCountryDto $country
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            ManufacturerCountryDto::fromState($data['country'])
        );
    }

    public function getId(): int
    {
        return $this->id;
    }
}
