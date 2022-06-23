<?php

namespace ApiFacade\EuroAuto\Responses\Product\FindByArticular;

use ApiFacade\EuroAuto\Responses\Product\Shared\ProductNameDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ResponseByArticularDto extends AbstractionEntity
{
    public function __construct(
        protected string $code,
        protected int $manufacturerId
    ) {
    }

    public static function fromState(array $data): self
    {
        $code = str_replace('-', '', $data['code']);

        return new self(
            $code,
            $data['manufacturer']['id']
        );
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getManufacturerId(): int
    {
        return $this->manufacturerId;
    }
}
