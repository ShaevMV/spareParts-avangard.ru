<?php

namespace ApiFacade\EuroAuto\Responses\Order;

use ApiFacade\EuroAuto\Responses\Shared\DeliveryDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class OrderDto extends AbstractionEntity
{
    public function __construct(
        protected DeliveryDto $delivery
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(DeliveryDto::fromState($data['delivery']));
    }

    public function getDelivery(): DeliveryDto
    {
        return $this->delivery;
    }
}
