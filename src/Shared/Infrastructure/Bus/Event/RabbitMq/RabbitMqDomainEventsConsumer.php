<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPEnvelope;
use AMQPQueueException;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use JsonException;

final class RabbitMqDomainEventsConsumer
{
    public function __construct(
        private RabbitMqConnection $connection,
        private DomainEventJsonDeserializer $deserializer,
    ) {
    }

    /**
     * @param  callable  $subscriber
     * @param  string  $queueName
     * @param  bool  $blocking
     * @return void
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws JsonException
     */
    public function consume(
        callable $subscriber,
        string $queueName,
        bool $blocking = true
    ): void {
        try {
            while (true) {
                $envelope = $this->connection->queue($queueName)->get();
                if (

                        $envelope instanceof AMQPEnvelope &&
                        is_string($envelope->getBody()) &&
                        $subscriber(
                            $this->deserializer->deserialize(
                                $envelope->getBody()
                            )
                        )
                    ) {
                    $this->connection->queue($queueName)->ack($envelope->getDeliveryTag());
                }

                if (!$blocking) {
                    break;
                }
            }
        } catch (AMQPQueueException $error) {
            // We don't want to raise an error if there are no messages in the queue
        }
    }
}
