<?php

namespace App\Providers;

use ApiFacade\EuroAuto\Order\Application\Create\OrderCreateDomainEventSubscriber;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConnection;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use App\Console\Commands\CreateOrderConsumerCommand;
use ArrayObject;
use Illuminate\Support\ServiceProvider;

class CreateOrderConsumerCommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(CreateOrderConsumerCommand::class)
            ->needs(DomainEventSubscriberLocator::class)
            ->give(function () {
                return new DomainEventSubscriberLocator(new ArrayObject([
                    $this->app->get(OrderCreateDomainEventSubscriber::class),
                ]));
            });


        $this->app->when(CreateOrderConsumerCommand::class)
            ->needs(RabbitMqDomainEventsConsumer::class)
            ->give(function () {
                return new RabbitMqDomainEventsConsumer(
                    new RabbitMqConnection(),
                    $this->app->get(DomainEventJsonDeserializer::class)
                );
            });
    }

}
