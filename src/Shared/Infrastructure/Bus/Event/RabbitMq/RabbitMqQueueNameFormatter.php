<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq;

use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;
use ApiFacade\Shared\Domain\Utils;
use function Lambdish\Phunctional\last;
use function Lambdish\Phunctional\map;

final class RabbitMqQueueNameFormatter
{
    public static function format(string $subscriber): string
    {
        $subscriberClassPaths = explode('\\', str_replace('ApiFacade', 'apifacade', $subscriber));

        $queueNameParts = [
            $subscriberClassPaths[0],
            $subscriberClassPaths[1],
            $subscriberClassPaths[2],
            last($subscriberClassPaths),
        ];

        return implode('.', map(self::toSnakeCase(), $queueNameParts));
    }

    public static function formatRetry(string $subscriber): string
    {
        $queueName = self::format($subscriber);

        return "retry.$queueName";
    }

    public static function formatDeadLetter(string $subscriber): string
    {
        $queueName = self::format($subscriber);

        return "dead_letter.$queueName";
    }

    private static function toSnakeCase(): callable
    {
        return static fn(string $text) => Utils::toSnakeCase($text);
    }
}
