<?php

namespace ApiFacade\EuroAuto\Cart\Application\Create;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPEnvelopeException;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use Illuminate\Console\Command;
use JsonException;

class CreateCartConsumerCommand extends Command
{
    protected $signature = 'rabbitMqConsumer:createCart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запустить consumer на прослушку канала в очереди, для создание корзины';

    /**
     * @throws AMQPEnvelopeException
     * @throws AMQPChannelException
     * @throws AMQPConnectionException
     * @throws JsonException
     */
    public function handle(
        RabbitMqDomainEventsConsumer $consumer,
        DomainEventSubscriberLocator $locator
    ): void {
        /** @var callable $subscriber */
        $subscriber = $locator->withRabbitMqQueueNamed(
            RabbitMqQueueNameFormatter::format(CartCreateDomainEventSubscriber::class)
        );
        $consumer->consume(
            $subscriber,
            RabbitMqQueueNameFormatter::format(CartCreateDomainEventSubscriber::class)
        );
    }
}
