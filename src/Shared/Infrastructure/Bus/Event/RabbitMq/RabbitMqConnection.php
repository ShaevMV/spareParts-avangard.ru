<?php

declare(strict_types=1);

namespace ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannel;
use AMQPConnection;
use AMQPConnectionException;
use AMQPExchange;
use AMQPExchangeException;
use AMQPQueue;
use AMQPQueueException;

final class RabbitMqConnection
{
    private static ?AMQPConnection $connection = null;
    private static ?AMQPChannel $channel = null;
    /** @var AMQPExchange[] */
    private static array $exchanges = [];
    /** @var AMQPQueue[] */
    private static array $queues = [];
    private static array $credentials;

    /**
     * @throws AMQPConnectionException
     */
    public function __construct()
    {
        self::$credentials = [
            'host' => env('RABBITMQ_HOST'),
            'port' => env('RABBITMQ_PORT'),
            'vhost' => env('RABBITMQ_VHOST'),
            'login' => env('RABBITMQ_USER'),
            'password' => env('RABBITMQ_PASSWORD'),
        ];
        self::$connection = new AMQPConnection(self::$credentials);

        self::$connection->connect();
        self::$channel = new AMQPChannel(self::$connection);
    }

    /**
     * @throws AMQPExchangeException
     * @throws AMQPConnectionException
     */
    public function exchange(string $name): AMQPExchange
    {
        if (!array_key_exists($name, self::$exchanges)) {
            $exchange = new AMQPExchange($this->channel());
            $exchange->setName($name);

            self::$exchanges[$name] = $exchange;
        }

        return self::$exchanges[$name];
    }

    /**
     * @throws AMQPConnectionException
     */
    private function channel(): AMQPChannel
    {
        if (!self::$channel?->isConnected()) {
            self::$channel = new AMQPChannel($this->connection());
        }

        return self::$channel;
    }

    /**
     * @throws AMQPConnectionException
     */
    private function connection(): AMQPConnection
    {
        if (null === self::$connection) {
            self::$connection = new AMQPConnection(self::$credentials);
        }

        if (!self::$connection->isConnected()) {
            self::$connection->pconnect();
        }

        return self::$connection;
    }

    /**
     * @throws AMQPConnectionException
     * @throws AMQPQueueException
     */
    public function queue(string $name): AMQPQueue
    {
        if (!array_key_exists($name, self::$queues)) {
            $queue = new AMQPQueue($this->channel());
            $queue->setName($name);

            self::$queues[$name] = $queue;
        }

        return self::$queues[$name];
    }
}
