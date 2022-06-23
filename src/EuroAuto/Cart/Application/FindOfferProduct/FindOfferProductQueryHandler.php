<?php

namespace ApiFacade\EuroAuto\Cart\Application\FindOfferProduct;

use ApiFacade\EuroAuto\Cart\Application\FindManufacturer\FindManufacturerQuery;
use ApiFacade\EuroAuto\Connect\EuroAutoProductApi;
use ApiFacade\Shared\Domain\Bus\Query\QueryHandler;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use Nette\Utils\JsonException;

class FindOfferProductQueryHandler implements QueryHandler
{
    public function __construct(
        private EuroAutoProductApi $autoProductApi
    ) {
    }

    /**
     * @throws JsonException
     * @throws ExceptionConnectApi
     */
    public function __invoke(FindOfferProductQuery $query): ResponseOffersList
    {
        return $this->autoProductApi->getProduct(
            $query->getManufacturerId(),
            $query->getCode()
        );
    }
}
