<?php

namespace ApiFacade\Shared\RabbitMQ\Queue;

use AMQPChannelException;
use AMQPConnection;
use AMQPConnectionException;
use AMQPEnvelope;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPChannel;
use AMQPQueueException;
use Exception;

class Amqp
{
    private AMQPConnection $connection;

    private AMQPExchange $exchange;

    private AMQPQueue $queue;

    /**
     * @throws Exception
     */
    public function __construct(
        string $queueName
    ) {
        $credentials = [
            'host' => env('RABBITMQ_HOST'),
            'port' => env('RABBITMQ_PORT'),
            'vhost' => env('RABBITMQ_VHOST'),
            'login' => env('RABBITMQ_USER'),
            'password' => env('RABBITMQ_PASSWORD'),
        ];
        $this->connection = new AMQPConnection($credentials);
        $this->connection->connect();
        $channel = new AMQPChannel($this->connection);
        $this->exchange = $this->getExchange($channel, (string) env('RABBITMQ_EXCHANGE_CART_NAME'));
        $this->queue = $this->getQuery($channel, $queueName);
        $this->queue->bind($this->exchange->getName(), (string) env('RABBITMQ_ROUTING_KEY'));
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    private function getExchange(AMQPChannel $channel, string $name): AMQPExchange
    {
        $exchange = new AMQPExchange($channel);
        $exchange->setName($name);
        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        $exchange->setFlags(AMQP_DURABLE);
        $exchange->declareExchange();

        return $exchange;
    }

    /**
     * @throws AMQPChannelException
     * @throws AMQPQueueException
     * @throws AMQPConnectionException
     */
    private function getQuery(AMQPChannel $channel, string $queryName): AMQPQueue
    {
        $queue = new AMQPQueue($channel);
        $queue->setName($queryName);
        $queue->setFlags(AMQP_IFUNUSED);
        $queue->declareQueue();

        return $queue;
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     */
    public function produce(string $data): bool
    {
        return $this->exchange->publish($data, (string) env('RABBITMQ_ROUTING_KEY'));
    }

    /**
     * @throws Exception
     */
    public function __destruct()
    {
        $this->connectionDisconnect();
    }

    public function connectionDisconnect(): void
    {
        $this->connection->disconnect();
    }

    /**
     * @throws Exception
     */
    public function consume(callable $callback, bool $isAutoLoop = true): void
    {
        while (true) {
            $envelope = $this->queue->get();

            if (
                $envelope instanceof AMQPEnvelope &&
                $callback($envelope->getBody())
            ) {
                $this->queue->ack($envelope->getDeliveryTag());
            }

            if (!$envelope && !$isAutoLoop) {
                break;
            }
        }
    }
}
