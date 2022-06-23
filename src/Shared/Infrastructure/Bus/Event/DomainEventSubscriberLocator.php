<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event;

use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;
use ApiFacade\Shared\Infrastructure\Bus\CallableFirstParameterExtractor;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use RuntimeException;
use Traversable;
use function Lambdish\Phunctional\search;

final class DomainEventSubscriberLocator
{
    private array $mapping;

    public function __construct(Traversable $mapping)
    {
        $this->mapping = iterator_to_array($mapping);
    }

    public function withRabbitMqQueueNamed(string $queueName): DomainEventSubscriber|callable
    {
        /** @var DomainEventSubscriber|callable|null $subscriber */
        $subscriber = search(
            static function(DomainEventSubscriber $subscriber) use ($queueName){
                $nameSubscriber = RabbitMqQueueNameFormatter::format(get_class($subscriber));

                return $nameSubscriber === $queueName;
            },
            $this->mapping
        );

        if (is_null($subscriber)) {
            throw new RuntimeException("There are no subscribers for the <$queueName> queue");
        }

        return $subscriber;
    }

    public function all(): array
    {
        return $this->mapping;
    }
}
