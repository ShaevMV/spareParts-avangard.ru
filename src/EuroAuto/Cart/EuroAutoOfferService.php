<?php

namespace ApiFacade\EuroAuto\Cart;

use ApiFacade\EuroAuto\Responses\Shared\OffersDto;

class EuroAutoOfferService
{
    private const MESSAGE_QUANTITY_LESS_THAN_REQUIRED = 'Заказано меньше кол-во товара {%count} из {%quantity}';

    private const MASK_OFFER = [
        '1-',
        '0-'
    ];

    /**
     * Вывести конкретное предложения
     *
     * @param  OffersDto[]  $offersDtoList
     * @param  int  $quantity
     * @return OffersDto|null
     */
    public function getCorrectOffers(
        array $offersDtoList,
        int $quantity
    ): ?OffersDto {
        if (empty($offersDtoList)) {
            return null;
        }

        usort($offersDtoList, static function (OffersDto $offersDto1, OffersDto $offersDto2) {
            return $offersDto1->getPrice() <=> $offersDto2->getPrice();
        });

        $correctOfferList = [];
        foreach ($offersDtoList as $offersDto) {
            if ($this->isCorrectOffer($offersDto->getId())) {
                $correctOfferList[] = $offersDto;
            }
        }

        if (count($correctOfferList) === 0) {
            return null;
        }

        usort($correctOfferList, static function (OffersDto $offersDto1, OffersDto $offersDto2) {
            return $offersDto1->getQuantity() <=> $offersDto2->getQuantity();
        });
        /** @var OffersDto $result */
        $result = reset($correctOfferList);
        if ($result->getQuantity() < $quantity) {
            $result->setMessage($this->getMessageLimit($result->getQuantity(), $quantity));
            $quantity = $result->getQuantity();
        }

        return $result->setQuantity($quantity);
    }

    /**
     * Получить корректный офер
     *
     * @param  string  $idOffer
     * @return bool
     */
    private function isCorrectOffer(string $idOffer): bool
    {
        foreach (self::MASK_OFFER as $item) {
            $pos = strpos($idOffer, $item);
            if ($pos !== false && $pos === 0) {
                return true;
            }
        }

        return false;
    }

    private function getMessageLimit(int $count, int $quantity): string
    {
        return str_replace([
            '{%count}',
            '{%quantity}'
        ], [
            $count, $quantity
        ], self::MESSAGE_QUANTITY_LESS_THAN_REQUIRED);
    }

}
