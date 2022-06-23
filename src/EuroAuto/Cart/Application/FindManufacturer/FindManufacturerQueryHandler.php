<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Cart\Application\FindManufacturer;

use ApiFacade\EuroAuto\Connect\EuroAutoProductApi;
use ApiFacade\EuroAuto\Responses\Product\FindByArticular\ResponseByArticularDto;
use ApiFacade\Shared\Domain\Bus\Query\QueryHandler;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use Nette\Utils\JsonException;
use Webmozart\Assert\Assert;
use Webmozart\Assert\InvalidArgumentException;

class FindManufacturerQueryHandler implements QueryHandler
{
    public function __construct(
        private EuroAutoProductApi $productApi,
    ) {
    }

    /**
     * @throws JsonException
     * @throws ExceptionConnectApi
     */
    public function __invoke(FindManufacturerQuery $query): ?ManufacturerResponse
    {
        /** @var ResponseByArticularDto|null $result */
        $result = $this->productApi->findByArticularAndManufacturer(
            $query->getCode(),
            $query->getManufacturerName(),
        );

        if(is_null($result)) {
            return null;
        }

        return new ManufacturerResponse(
            $result->getManufacturerId(),
            $query->getManufacturerName()
        );
    }
}
