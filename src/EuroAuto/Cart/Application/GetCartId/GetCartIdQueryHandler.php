<?php

namespace ApiFacade\EuroAuto\Cart\Application\GetCartId;

use ApiFacade\EuroAuto\Cart\Repositories\InMemoryCartRepository;
use ApiFacade\EuroAuto\Helpers\EuroAutoRedisHelper;
use ApiFacade\EuroAuto\Responses\Carts\NewCart\CartDto;
use ApiFacade\Shared\Domain\Bus\Query\QueryHandler;
use Nette\Utils\JsonException;

class GetCartIdQueryHandler implements QueryHandler
{
    public function __construct(
        private InMemoryCartRepository $repository
    ) {
    }

    /**
     * @throws JsonException
     */
    public function __invoke(GetCartIdQuery $query): ?CartDto
    {
        return $this->repository->getCartId(EuroAutoRedisHelper::getCartIdKey($query->getBecome()));
    }
}
