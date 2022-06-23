<?php

namespace ApiFacade\EuroAuto\Responses\Order\Items;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class OrderItemDto extends AbstractionEntity
{
    public function __construct(
        protected string $id,
        protected string $order_id,
        protected OrderStateDto $state,
        protected string $offer_id,
        protected int $price,
        protected int $quantity,
        protected string $product_id,
        protected string $created_at,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['order']['id'],
            OrderStateDto::fromState($data['state']),
            $data['offer_id'],
            $data['price'],
            $data['quantity'],
            $data['product_id'],
            $data['created_at']
        );
    }

    public function getOrderId(): string
    {
        return $this->order_id;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @return OrderStateDto
     */
    public function getState(): OrderStateDto
    {
        return $this->state;
    }
}
