<?php

namespace ApiFacade\EuroAuto\Responses\Product\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ManufactureCodesDto extends AbstractionEntity
{
    public function __construct(
        protected string $code,
        protected ManufacturerCountryDto $manufacturer,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['code'],
            ManufacturerCountryDto::fromState($data['manufacturer'])
        );
    }
}
