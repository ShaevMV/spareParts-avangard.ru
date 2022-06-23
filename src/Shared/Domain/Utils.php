<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Domain;

use ApiFacade\Shared\Domain\Entity\AbstractionEntity;
use ApiFacade\Shared\Domain\Entity\EntityInterface;
use Closure;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use JsonException;
use ReflectionClass;
use RuntimeException;
use function Lambdish\Phunctional\filter;

final class Utils
{

    public static function endsWith(string $needle, string $haystack): bool
    {
        $length = strlen($needle);
        if ($length === 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

    public static function dateToString(DateTimeInterface $date): string
    {
        return $date->format(DateTimeInterface::ATOM);
    }

    /**
     * @throws Exception
     */
    public static function stringToDate(string $date): DateTimeImmutable
    {
        return new DateTimeImmutable($date);
    }

    /**
     * @throws JsonException
     */
    public static function jsonEncode(array $values): string
    {
        return json_encode($values, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws JsonException
     */
    public static function jsonDecode(string $json): array
    {
        /** @var array $data */
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: '.json_last_error());
        }

        return $data;
    }

    public static function toSnakeCase(string $text): string
    {
        return ctype_lower($text) ? $text : strtolower(preg_replace('/([^A-Z\s])([A-Z])/', "$1_$2", $text) ?? $text);
    }
}
