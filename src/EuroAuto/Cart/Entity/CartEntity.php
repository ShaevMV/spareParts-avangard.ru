<?php

namespace ApiFacade\EuroAuto\Cart\Entity;

use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;
use Illuminate\Support\Facades\Log;

class CartEntity extends AbstractionEntity
{
    /**
     * @param  string  $id
     * @param  string  $become
     * @param  array<string|int ,OffersDto|null>  $offers
     * @param  array  $nullProduct
     * @param  array  $nullOffer
     */
    public function __construct(
        protected string $id,
        protected string $become,
        protected array $offers,
        protected array $nullProduct = [],
        protected array $nullOffer = [],
    ) {
    }

    public static function fromState(array $data): self
    {
        $offers = [];

        foreach ($data['offers'] as $key => $offer) {
            $offers[$key] = null === $offer ? null : OffersDto::fromState($offer);
        }
        $b=4;
        return new self(
            $data['id'],
            $data['become'],
            $offers,
            $data['nullProduct'],
            $data['nullOffer'],
        );
    }

    public function getBecome(): string
    {
        return $this->become;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNullProduct(): array
    {
        return $this->nullProduct;
    }

    public function getNullOffer(): array
    {
        return $this->nullOffer;
    }
}
