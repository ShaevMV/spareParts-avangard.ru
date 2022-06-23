<?php

namespace ApiFacade\EuroAuto\Helpers;

abstract class EuroAutoHelperAbstract
{
    public static function replace(string $rawString, array $params = []): string
    {
        if (count($params) > 0) {
            foreach ($params as $key => $param) {
                $rawString = str_replace('{'.$key.'}', $param, $rawString);
            }
        }

        return $rawString;
    }
}
