<?php

namespace ApiFacade\EuroAuto\Cart\Application\FindManufacturer;

use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

final class ManufacturerResponse extends AbstractionEntity implements Response
{
    public function __construct(
        protected int $manufacturerId,
        protected string $manufacturerName,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['manufacturerId'],
            $data['manufacturerName']
        );
    }

    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }

    public function getManufacturerName(): string
    {
        return $this->manufacturerName;
    }
}
