<?php

namespace ApiFacade\Tests\Shared\Infrastructure\Bus\Event\RabbitMq;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use ApiFacade\EuroAuto\Cart\Application\Create\CartCreateDomainEventSubscriber;
use ApiFacade\EuroAuto\Cart\Domain\CartCreatingDomainEvent;
use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreateDomainEventSubscriber;
use ApiFacade\Shared\Domain\Bus\Event\DomainEvent;
use ApiFacade\Shared\Domain\Entity\EntityMapping;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConfigures;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConnection;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqEventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use ApiFacade\Shared\Infrastructure\Bus\Event\Sentry\SentryEventBus;
use ArrayObject;
use Exception;
use JsonException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;
use Webpatser\Uuid\Uuid;

class RabbitMqEventBusTest extends TestCase
{
    private RabbitMqConfigures $configures;
    private RabbitMqEventBus $publisher;
    private RabbitMqDomainEventsConsumer $consumer;
    private bool $consumerHasBeenExecuted;

    /**
     * @return void
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws AMQPExchangeException
     * @throws JsonException
     * @throws Exception
     */
    public function test_it_should_publish_and_consume_domain_events_from_rabbitmq(): void
    {
        /** @var CartCreateDomainEventSubscriber $fakeSubscriber */
        $fakeSubscriber = $this->app->get(CartCreateDomainEventSubscriber::class);
        $become = Uuid::generate()->string;
        $data = [
            'become' => $become,
            'particulars' => EntityMapping::flat([
                ArticularRawDto::fromState([
                    'articular' => '10123321-104C',
                    'quantity' => 1,
                    'manufacture' => 'Metaco',
                ])
            ]),
        ];

        $domainEvent = CartCreatingDomainEvent::fromPrimitives(
            Uuid::generate()->string,
            $data
        );
        $this->configures->configure(env('RABBITMQ_EXCHANGE_CART_NAME'), $fakeSubscriber);
        $this->publisher->publish($domainEvent);
        $this->consumer->consume(
            $this->assertConsumer($domainEvent),
            RabbitMqQueueNameFormatter::format(get_class($fakeSubscriber))
        );

        self::assertTrue($this->consumerHasBeenExecuted);
    }

    private function assertConsumer(DomainEvent ...$expectedDomainEvents): callable
    {
        return function (DomainEvent $domainEvent) use ($expectedDomainEvents): void {
            $this->assertContainsEquals($domainEvent, $expectedDomainEvents);

            $this->consumerHasBeenExecuted = true;
        };
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        /** @var SentryEventBus $sentry */
        $sentry = $this->app->get(SentryEventBus::class);
        $this->app->bind(DomainEventMapping::class, function () {
            return new DomainEventMapping([
                $this->app->get(CartCreateDomainEventSubscriber::class),
                $this->app->get(OrderCreateDomainEventSubscriber::class),
            ]);
        });

        $this->app->bind(DomainEventSubscriberLocator::class, function () {
            return new DomainEventSubscriberLocator(new ArrayObject([
                $this->app->get(CartCreateDomainEventSubscriber::class),
                $this->app->get(OrderCreateDomainEventSubscriber::class),
            ]));
        });
        $connection = new RabbitMqConnection();

        $this->configures = new RabbitMqConfigures($connection);

        $this->publisher = new RabbitMqEventBus(
            $connection,
            $sentry
        );
        /** @var DomainEventJsonDeserializer $domainEventJsonDeserializer */
        $domainEventJsonDeserializer = $this->app->get(DomainEventJsonDeserializer::class);
        $this->consumer = new RabbitMqDomainEventsConsumer(
            $connection,
            $domainEventJsonDeserializer
        );
        $this->consumerHasBeenExecuted = false;
    }
}
