<?php

namespace ApiFacade\Tests\EuroAuto\Cart\Application\CartCreate;

use ApiFacade\EuroAuto\Cart\Application\Create\CartCreate;
use ApiFacade\EuroAuto\Cart\Application\FindOfferProduct\ResponseOffersList;
use ApiFacade\EuroAuto\Cart\Domain\CartCreatingDomainEvent;
use ApiFacade\EuroAuto\Cart\Dto\ArticularRawDto;
use ApiFacade\EuroAuto\Connect\EuroAutoCartApi;
use ApiFacade\EuroAuto\Connect\EuroAutoProductApi;
use ApiFacade\EuroAuto\Responses\Carts\NewCart\CartInfoDto;
use ApiFacade\EuroAuto\Responses\Product\FindByArticular\ResponseByArticularDto;
use ApiFacade\Shared\Domain\Entity\EntityMapping;
use Exception;
use Mockery\ExpectationInterface;
use Mockery\MockInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\TestCase;
use Throwable;
use Webpatser\Uuid\Uuid;

class CartCreateTest extends TestCase
{
    private CartCreate $cartCreate;

    /**
     * @dataProvider dataProviderArticular
     *
     * @throws Throwable
     */
    public function test_insert_product_in_cart(
        array $particularsRawListDto,
        string $become
    ): void {
        $data = [
            'become' => $become,
            'particulars' => EntityMapping::flat(
                $particularsRawListDto
            ),
        ];

        $cart = $this->cartCreate->create(CartCreatingDomainEvent::fromPrimitives(
            Uuid::generate()->string,
            $data
        ));

        self::assertEquals(69421, $cart->getId());
        self::assertEquals($become, $cart->getBecome());
    }

    /**
     * @throws Exception
     */
    public function dataProviderArticular(): array
    {
        return [
            [
                array(
                    ArticularRawDto::fromState([
                        'articular' => '1010-104C',
                        'quantity' => 1,
                        'manufacture' => 'Metaco',
                    ]),
                ),
                Uuid::generate()->string
            ]
        ];
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mock(EuroAutoProductApi::class,
            static function (MockInterface $mock) {
                /** @var ExpectationInterface $method */
                $method = $mock->shouldReceive('findByArticularAndManufacturer');
                $method->andReturn(ResponseByArticularDto::fromState([
                    'manufacturer' => [
                        'id' => 2775,
                    ],
                    'code' => '1010-104C',
                ]));
                /** @var ExpectationInterface $method */
                $method = $mock->shouldReceive('getProduct');
                $method->andReturn(ResponseOffersList::fromState([
                    'offers' => [
                        [
                            'id' => '1-2170-3181799-0-0',
                            'store_id' => '2170',
                            'product_id' => '0-0-3181799-0',
                            'quantity' => 477,
                            'price' => 603
                        ],
                        [
                            'id' => '2-2170-3181799-0-1',
                            'store_id' => '2170',
                            'product_id' => '0-0-3181799-0',
                            'quantity' => 477,
                            'price' => 746
                        ],
                    ],
                ]));
            }
        );
        $this->mock(EuroAutoCartApi::class,
            static function (MockInterface $mock) {
                /** @var ExpectationInterface $method */
                $method = $mock->shouldReceive('createOrAddCart');
                $method->andReturn(CartInfoDto::fromState([
                    'cart' => [
                        'id' => "69421",
                        'labels' => [
                            'user_order' => "8ea15dd0-e714-11ec-813f-6db7a6f21ce8"
                        ]
                    ],
                ]));
            }
        );
        /** @var CartCreate $cartCreate */
        $cartCreate = $this->app->get(CartCreate::class);
        $this->cartCreate = $cartCreate;
    }
}
