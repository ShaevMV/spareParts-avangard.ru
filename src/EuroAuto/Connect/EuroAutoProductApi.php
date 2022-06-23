<?php

namespace ApiFacade\EuroAuto\Connect;

use ApiFacade\EuroAuto\Cart\Application\FindOfferProduct\ResponseOffersList;
use ApiFacade\EuroAuto\Helpers\EuroAutoHelper;
use ApiFacade\EuroAuto\Responses\Product\FindByArticular\ResponseByArticularDto;
use ApiFacade\EuroAuto\Responses\Shared\DeliveryDto;
use ApiFacade\EuroAuto\Responses\Shared\OffersDto;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionNotFound;
use Nette\Utils\JsonException;

class EuroAutoProductApi extends EuroAutoSubmitAnInquiry
{
    /**
     * Поиск артикулов производителей
     *
     * @throws ExceptionConnectApi|JsonException
     */
    public function findByArticularAndManufacturer(
        string $code,
        ?string $manufacturer = null
    ): ?ResponseByArticularDto {
        $params = [
            'code' => $code,
        ];

        if (!is_null($manufacturer)) {
            $params['manufacturer_name'] = $manufacturer;
        }

        $response = $this->connect->get(
            EuroAutoHelper::getUrl(EuroAutoHelper::FIND_BY_ARTICLES_URL),
            $params
        );

        $result = $this->getResultResponse($response);

        if (!count($result)) {
            return null;
        }

        return ResponseByArticularDto::fromState(end(
            $result
        ));
    }

    /**
     * Получить предложения по товару и сам товар
     *
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function getProduct(
        int $manufacturerId,
        string $articular,
        string $type = EuroAutoHelper::NEW_PRODUCT
    ): ResponseOffersList {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::GET_PRODUCT_URL, [
            'manufacturer_id' => $manufacturerId,
            'type' => $type,
        ]);

        $response = $this->connect->get(
            $url, [
            'code' => $articular,
            'store_id[]' => implode(',', EuroAutoHelper::STORE_ID),
        ]);
        $data = $this->getResultResponse($response);

        return ResponseOffersList::fromState($data);
    }

    /**
     * Получить детальную информацию о предложениях
     *
     * @param  OffersDto[]  $offersDto
     *
     * @return array<string|int,DeliveryDto>
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function getDeliveryByOfferInfo(array $offersDto): array
    {
        $offerIds = [];

        // TODO: Ограничить до 10 шт.
        foreach ($offersDto as $offerDto) {
            $offerIds[] = $offerDto->getId();
        }

        $url = EuroAutoHelper::getUrl(EuroAutoHelper::GET_OFFER_INFO_URL);
        //$url = EuroAutoHelper::getUrlAndOption($urlRaw, 'offer_id[]', $offerIds);


        $response = $this->connect->get($url, [
            'offer_ids' => $offerIds
        ]);

        $data = $this->getResultResponse($response);

        $result = [];
        foreach ($data['delivery'] as $datum) {
            $temp = DeliveryDto::fromState($datum);
            foreach ($temp->getOfferIds() as $offerId) {
                $result[$offerId] = $temp;
            }
        }

        return $result;
    }
}
