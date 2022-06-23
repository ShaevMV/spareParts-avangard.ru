<?php

namespace ApiFacade\EuroAuto\Cart\Application\FindOfferProduct;

use ApiFacade\EuroAuto\Helpers\EuroAutoHelper;
use ApiFacade\EuroAuto\Responses\Product\GetProduct\CursorDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ManufactureCodesDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\ProductsDto;
use ApiFacade\EuroAuto\Responses\Product\Shared\StoresDto;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Domain\Bus\Query\Response;
use ApiFacade\Shared\Domain\Entity\AbstractionEntity;

class ResponseOffersList extends AbstractionEntity implements Response
{
    /**
     * @param  OffersDto[]  $offers
     */
    public function __construct(
        protected array $offers,
    ) {
    }

    public static function fromState(array $data): self
    {
        $offers = [];
        foreach ($data['offers'] as $offer) {
            $offers[] = OffersDto::fromState($offer);
        }

        return new self(
            $offers,
        );
    }

    public function isFind(): bool
    {
        return count($this->offers) > 0;
    }

    public function getOffers(): array
    {
        return $this->offers;
    }
}
