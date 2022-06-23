<?php

namespace ApiFacade\EuroAuto\Responses\Shared;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class OffersDto extends AbstractionEntity
{
    public function __construct(
        protected string $id,
        protected string $store_id,
        protected string $product_id,
        protected int $quantity,
        protected int $price,
        protected ?string $message = null,
    ) {
    }

    public static function fromState(array $data): self
    {
        return new self(
            $data['id'],
            $data['store_id'],
            $data['product_id'],
            $data['quantity'],
            $data['price'],
            $data['message'] ?? null
        );
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}
