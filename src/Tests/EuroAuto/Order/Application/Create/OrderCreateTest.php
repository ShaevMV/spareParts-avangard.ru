<?php

namespace ApiFacade\Tests\EuroAuto\Order\Application\Create;

use ApiFacade\EuroAuto\Cart\Domain\Cart;
use ApiFacade\EuroAuto\Connect\EuroAutoOrderApi;
use ApiFacade\EuroAuto\Order\Application\Create\OrderCreate;
use ApiFacade\EuroAuto\Order\Domain\OrderCreatingDomainEvent;
use ApiFacade\EuroAuto\Responses\Order\OrderInfoDto;
use Mockery\ExpectationInterface;
use Mockery\MockInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;
use Throwable;
use Webpatser\Uuid\Uuid;

class OrderCreateTest extends TestCase
{
    private OrderCreate $orderCreate;

    /**
     * @throws Throwable
     */
    public function test_creating_order_by_cart(): void
    {
        $order = $this->orderCreate->create(new OrderCreatingDomainEvent(
            Uuid::generate()->string,
            'd1f6fb40-e56f-11ec-b20a-f335c4ae7ea3',
            Cart::fromState([
                'id' => '69177',
                'become' => 'd1f6fb40-e56f-11ec-b20a-f335c4ae7ea3',
                'offers' => [
                    '1010-104C' => [
                        'id' => '1-2170-3181799-0-0',
                        'store_id' => '2170',
                        'product_id' => '0-0-3181799-0',
                        'quantity' => 1,
                        'price' => 603,
                        'message' => null
                    ],
                ],
                'nullProduct' => [],
                'nullOffer' => [],
            ])
        ));
        self::assertEquals('1-16506534', $order->getId());
        self::assertEquals('d1f6fb40-e56f-11ec-b20a-f335c4ae7ea3', $order->getBecome());
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(EuroAutoOrderApi::class, static function (MockInterface $mock) {
            /** @var ExpectationInterface $method */
            $method = $mock->shouldReceive('createOrderByCartId');

            $method->andReturn(
                OrderInfoDto::fromState([
                    'orders' => [
                        '1-16506534' => [],
                    ],
                    'products' => [
                        '0-2170-3181799-0' => [
                            'name' => [
                                'id' => '3051',
                                'name' => '1010-104C',
                            ],
                            'quantity' => 1,
                            "price" => 603,
                            'comment' => 'MERCEDES BENZ W203/ C209 CLK COUPE',
                            'condition' => 'NEW',
                            'manufacturer_code' => "3181799",
                            'manufacture' => 'Metaco',
                        ],
                    ],
                ])
            );
        });
        /** @var OrderCreate $orderCreate */
        $orderCreate = $this->app->get(OrderCreate::class);
        $this->orderCreate = $orderCreate;
    }
}
