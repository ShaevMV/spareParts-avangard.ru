<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Order\Application\Create;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPExchangeException;
use ApiFacade\EuroAuto\Order\Domain\ErrorOrderDomainEvent;
use ApiFacade\EuroAuto\Order\Domain\OrderCreatingDomainEvent;
use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;
use ApiFacade\Shared\Domain\Bus\Event\EventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConfigures;
use ApiFacade\Shared\Order\ExceptionOrder;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Throwable;
use Webpatser\Uuid\Uuid;

class OrderCreateDomainEventSubscriber implements DomainEventSubscriber
{
    public function __construct(
        private OrderCreate $orderCreate,
        private RabbitMqConfigures $configures,
        private EventBus $bus,
    ) {
    }

    public static function subscribedTo(): array
    {
        return [
            OrderCreatingDomainEvent::class
        ];
    }

    /**
     * @param  OrderCreatingDomainEvent  $event
     * @return bool
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws AMQPExchangeException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws Throwable
     */
    public function __invoke(OrderCreatingDomainEvent $event): bool
    {
        try {
            $order = $this->orderCreate->create($event);
            /** @var OrderPushingDomainEventSubscriber $orderPushingDomainEventSubscriber */
            $orderPushingDomainEventSubscriber = app()->get(OrderPushingDomainEventSubscriber::class);
            /** @var string $exchangeName */
            $exchangeName = env('RABBITMQ_EXCHANGE_CART_NAME');
            $this->configures->configure(
                $exchangeName,
                $orderPushingDomainEventSubscriber
            );

            $this->bus->publish(...$order->pullDomainEvents());
        } catch (ExceptionOrder $exception) {
            $errorMassage = new ErrorOrderDomainEvent(
                Uuid::generate()->string,
                $event->getBecome(),
                $exception->getMessage()
            );
            $this->configures->configure(
                env('RABBITMQ_EXCHANGE_ERROR_NAME'),
                app()->get($errorMassage::class)
            );
            $this->bus->publish($errorMassage);

            throw $exception;
        }

        return true;
    }
}
