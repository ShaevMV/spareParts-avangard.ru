<?php

namespace ApiFacade\EuroAuto\Connect;

use ApiFacade\EuroAuto\Helpers\EuroAutoHelper;
use ApiFacade\EuroAuto\Responses\Order\OrderInfoDto;
use ApiFacade\EuroAuto\Responses\Order\OrderListDto;
use ApiFacade\Shared\Infrastructure\ConnectApi\Domain\ExceptionConnectApi;
use ApiFacade\Shared\Order\ExceptionOrder;
use Nette\Utils\JsonException;

class EuroAutoOrderApi extends EuroAutoSubmitAnInquiry
{
    /**
     * Создать заказ на основе на корзины
     *
     * @throws ExceptionConnectApi
     * @throws JsonException
     * @throws ExceptionOrder
     */
    public function createOrderByCartId(string $cartId, string $become): OrderInfoDto
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::CREATE_ORDER_URL);
        $response = $this->connect->asForm()->post($url, [
            'cart_id' => $cartId,
        ]);

        $data = $this->getResultResponse($response);
        if (isset($data['errors']) && !empty($data['errors']) && is_array($data['errors'])) {
            throw new ExceptionOrder("Не удалось создать заказа из корзины $cartId заказа $become ".implode(' ', $data['errors']));
        }

        return OrderInfoDto::fromState($data);
    }

    /**
     * Вывести список заказов
     *
     * @throws ExceptionConnectApi
     * @throws JsonException
     */
    public function getListOrder(): OrderListDto
    {
        $url = EuroAutoHelper::getUrl(EuroAutoHelper::GET_ORDER_LIST_URL);
        $response = $this->connect->get($url);
        $data = $this->getResultResponse($response);

        return OrderListDto::fromState($data);
    }
}
