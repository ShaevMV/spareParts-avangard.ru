<?php

declare(strict_types=1);

namespace ApiFacade\EuroAuto\Cart\Application\Create;

use ApiFacade\EuroAuto\Cart\Domain\CartCreatingDomainEvent;
use ApiFacade\EuroAuto\Cart\Repositories\CartRepositoryInterface;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreateDomainEventSubscriber;
use ApiFacade\EuroAuto\Order\Domain\ErrorOrderDomainEvent;
use ApiFacade\Shared\Domain\Bus\Event\DomainEventSubscriber;
use ApiFacade\Shared\Domain\Bus\Event\EventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConfigures;
use ApiFacade\Shared\Order\ExceptionOrder;
use Nette\Utils\JsonException;
use Throwable;
use Webpatser\Uuid\Uuid;

final class CartCreateDomainEventSubscriber implements DomainEventSubscriber
{
    public function __construct(
        private CartCreate $cartCreate,
        private RabbitMqConfigures $configures,
        private EventBus $bus,
    ) {
    }

    public static function subscribedTo(): array
    {
        return [
            CartCreatingDomainEvent::class
        ];
    }

    /**
     * @throws JsonException
     * @throws Throwable
     */
    public function __invoke(CartCreatingDomainEvent $event): bool
    {
        try {
            $cart = $this->cartCreate->create($event);
            /** @var OrderCreateDomainEventSubscriber $orderCreateDomainEventSubscriber */
            $orderCreateDomainEventSubscriber = app()->get(OrderCreateDomainEventSubscriber::class);
            /** @var string $exchangeName */
            $exchangeName = env('RABBITMQ_EXCHANGE_CART_NAME');
            $this->configures->configure(
                $exchangeName,
                $orderCreateDomainEventSubscriber
            );

            $this->bus->publish(...$cart->pullDomainEvents());
        } catch (ExceptionOrder $exception) {
            $errorMassage = new ErrorOrderDomainEvent(
                Uuid::generate()->string,
                $event->getBecome(),
                $exception->getMessage()
            );
            /** @var string $exchangeName */
            $exchangeName = env('RABBITMQ_EXCHANGE_CART_NAME');
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
