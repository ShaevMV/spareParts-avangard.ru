<?php

namespace App\Console\Commands;

use AMQPChannelException;
use AMQPConnectionException;
use AMQPEnvelopeException;
use ApiFacade\EuroAuto\Cart\Application\Create\CartCreateDomainEventSubscriber;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use Illuminate\Console\Command;
use JsonException;

class CreateCartConsumerCommand extends Command
{
    protected $signature = 'rabbitMqConsumer:createCart';

    protected $description = 'Запустить consumer на прослушку канала в очереди, для создание корзины';

    public function __construct(
        private RabbitMqDomainEventsConsumer $consumer,
        private DomainEventSubscriberLocator $locator,
    ) {
        parent::__construct();
    }

    /**
     * @throws AMQPChannelException
     * @throws JsonException
     * @throws AMQPConnectionException
     */
    public function handle(): void
    {
        $subscriber = $this->locator->withRabbitMqQueueNamed(
            RabbitMqQueueNameFormatter::format(CartCreateDomainEventSubscriber::class)
        );

        $this->consumer->consume(
            $subscriber,
            RabbitMqQueueNameFormatter::format(CartCreateDomainEventSubscriber::class)
        );
    }
}
