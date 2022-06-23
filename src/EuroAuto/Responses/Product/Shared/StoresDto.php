<?php

namespace ApiFacade\EuroAuto\Responses\Product\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class StoresDto extends AbstractionEntity
{
    public function __construct(
        protected string $name,
        protected string $address,
        protected string $phone,
        protected array $coordinates,
    )
    {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['name'],
            $data['address'],
            $data['phone'],
            $data['coordinates']
        );
    }
}
