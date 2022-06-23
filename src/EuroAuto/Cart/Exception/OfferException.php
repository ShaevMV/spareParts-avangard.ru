<?php

namespace ApiFacade\EuroAuto\Cart\Exception;

use Exception;
use Throwable;

class OfferException extends Exception
{
    public function __construct(
        array $nullProduct,
        array $nullOffers,
        string $message = "",
        int $code = 0,
        ?Throwable $previous = null
    ) {
        if (count($nullProduct) > 0) {
            $message .= 'Данные артикулы на не найдены в euroAuto : '.implode(',', $nullProduct).PHP_EOL;
        }

        if (count($nullOffers) > 0) {
            $message .= 'По данным артикулам не найдено подходящего офера в euroAuto : '.implode(',',
                    $nullOffers).PHP_EOL;
        }

        parent::__construct($message, $code, $previous);
    }
}
