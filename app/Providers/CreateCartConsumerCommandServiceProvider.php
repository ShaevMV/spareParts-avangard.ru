<?php

namespace App\Providers;

use ApiFacade\EuroAuto\Cart\Application\Create\CartCreateDomainEventSubscriber;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventJsonDeserializer;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventMapping;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqConnection;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use ApiFacade\Tests\Shared\Infrastructure\Bus\Event\RabbitMq\TestAllWorksOnRabbitMqEventsPublished;
use App\Console\Commands\CreateCartConsumerCommand;
use ArrayObject;
use Illuminate\Support\ServiceProvider;

class CreateCartConsumerCommandServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(CreateCartConsumerCommand::class)
            ->needs(DomainEventSubscriberLocator::class)
            ->give(function (){
                return new DomainEventSubscriberLocator(new ArrayObject([
                    $this->app->get(CartCreateDomainEventSubscriber::class),
                    $this->app->get(TestAllWorksOnRabbitMqEventsPublished::class)
                ]));
            });


        $this->app->when(CreateCartConsumerCommand::class)
            ->needs(RabbitMqDomainEventsConsumer::class)
            ->give(function () {
                return new RabbitMqDomainEventsConsumer(
                    new RabbitMqConnection(),
                    $this->app->get(DomainEventJsonDeserializer::class)
                );
            });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
