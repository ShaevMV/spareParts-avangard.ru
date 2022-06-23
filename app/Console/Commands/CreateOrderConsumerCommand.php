<?php

namespace App\Console\Commands;

use AMQPChannelException;
use AMQPConnectionException;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreateDomainEventSubscriber;
use ApiFacade\Shared\Infrastructure\Bus\Event\DomainEventSubscriberLocator;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqDomainEventsConsumer;
use ApiFacade\Shared\Infrastructure\Bus\Event\RabbitMq\RabbitMqQueueNameFormatter;
use Illuminate\Console\Command;
use JsonException;

class CreateOrderConsumerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitMqConsumer:createOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Запустить consumer на прослушку канала в очереди, для создание заказа из корзины';

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
            RabbitMqQueueNameFormatter::format(OrderCreateDomainEventSubscriber::class)
        );

        $this->consumer->consume(
            $subscriber,
            RabbitMqQueueNameFormatter::format(OrderCreateDomainEventSubscriber::class)
        );
    }
}
