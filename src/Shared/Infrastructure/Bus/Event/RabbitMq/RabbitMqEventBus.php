<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPException;
use AMQPExchangeException;
use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use ApiFacade\Shared\Domain\Bus\Event\EventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventJsonSerializer;
use ApiFacade\Shared\Infrastructure\Bus\Event\Sentry\SentryEventBus;
use JsonException;
use function Lambdish\Phunctional\each;

final class RabbitMqEventBus implements EventBus
{
    private RabbitMqConnection $connection;
    private string $exchangeName;
    private SentryEventBus $failoverPublisher;

    public function __construct(
        RabbitMqConnection $connection,
        SentryEventBus $failoverPublisher,
        ?string $exchangeName = null,
    ) {
        $this->connection = $connection;
        /** @var string $exchangeName */
        $exchangeName = $exchangeName ?? env('RABBITMQ_EXCHANGE_CART_NAME');
        $this->exchangeName = $exchangeName;
        $this->failoverPublisher = $failoverPublisher;
    }

    public function publish(DomainEvent ...$events): void
    {
        each($this->publisher(), $events);
    }

    private function publisher(): callable
    {
        return function (DomainEvent $event) {
            try {
                $this->publishEvent($event);
            } catch (AMQPException $error) {
                $this->failoverPublisher->publish($event);
            }
        };
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws JsonException
     * @throws AMQPConnectionException
     */
    private function publishEvent(DomainEvent $event): void
    {
        $body = DomainEventJsonSerializer::serialize($event);
        $routingKey = $event::eventName();
        $messageId = $event->eventId();

        $this->connection->exchange($this->exchangeName)->publish(
            $body,
            $routingKey,
            AMQP_NOPARAM,
            [
                'message_id' => $messageId,
                'content_type' => 'application/json',
                'content_encoding' => 'utf-8',
            ]
        );
    }
}
