<?php

namespace App\Providers;

use ApiFacade\EuroAuto\Auth\ConnectTokenApplication;
use ApiFacade\EuroAuto\Auth\InMemoryTokenRepository;
use ApiFacade\EuroAuto\Cart\Application\Create\CartCreateDomainEventSubscriber;
use ApiFacade\EuroAuto\Cart\Repositories\CartRepositoryInterface;
use ApiFacade\EuroAuto\Cart\Repositories\InMemoryCartRepository;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreate;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreateDomainEventSubscriber;
use ApiFacade\EuroAuto\Order\Repositories\InMemoryOrderRepository;
use ApiFacade\EuroAuto\Order\Repositories\OrderRepositoryInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthApplicationInterface;
use ApiFacade\Shared\Auth\Domain\Authenticate\AuthRepositoryInterface;
use ApiFacade\Shared\Domain\Bus\Event\EventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConfigures;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqEventBus;
use ApiFacade\Shared\Infrastructure\Bus\Event\Sentry\SentryEventBus;
use ArrayObject;
use Illuminate\Support\ServiceProvider;

class CartCreateDomainEventSubscriberServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->bind(AuthApplicationInterface::class, ConnectTokenApplication::class);
        $this->app->bind(AuthRepositoryInterface::class, InMemoryTokenRepository::class);
        $this->app->bind(CartRepositoryInterface::class, InMemoryCartRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, InMemoryOrderRepository::class);
        $this->app->when(OrderCreateDomainEventSubscriber::class)
            ->needs(EventBus::class)
            ->give(RabbitMqEventBus::class);


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

        $this->app->when(CartCreateDomainEventSubscriber::class)
            ->needs(EventBus::class)
            ->give(RabbitMqEventBus::class);

        $this->app->when(CartCreateDomainEventSubscriber::class)
            ->needs(CartRepositoryInterface::class)
            ->give(InMemoryCartRepository::class);


    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
