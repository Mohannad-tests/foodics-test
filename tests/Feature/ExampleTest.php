<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Mail;
use FoodicsTest\Mail\LowStockIngredientMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        Mail::fake();

        $this->getjson('/api/stock/1')
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    [
                        'name'     => 'Beef',
                        'quantity' => 22000,
                    ],
                    [
                        'name'     => 'Cheese',
                        'quantity' => 5000,
                    ],
                    [
                        'name'     => 'Onion',
                        'quantity' => 1000,
                    ],
                ],
            ]);

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 2,
                ],
            ],
        ])
            ->assertSuccessful()
            ->assertJsonPath('data.products.0.id', 1)
            ->assertJsonPath('data.products.0.pivot.quantity', 2);

        $this->getjson('/api/stock/1')
            ->assertSuccessful()
            ->assertJsonPath('data.0.quantity', 21700);

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 70,
                ],
            ],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Not enough Onion to make Burger. Wanted 1400 but only 960 available.');

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 20,
                ],
            ],
        ])->assertSuccessful();

        $this->getjson('/api/stock/1')
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    [
                        'name'     => 'Beef',
                        'quantity' => 18700,
                    ],
                    [
                        'name'     => 'Cheese',
                        'quantity' => 4340,
                    ],
                    [
                        'name'     => 'Onion',
                        'quantity' => 560,
                    ],
                ],
            ]);

        // After this point, the Onion quantity will be below the
        // recommended quantity, we will make two orders to make
        // sure that the mail is only sent once.

        Mail::assertNothingOutgoing();

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 10,
                ],
            ],
        ])->assertSuccessful();

        $this->getjson('/api/stock/1')
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    [
                        'name'     => 'Beef',
                        'quantity' => 17200,
                    ],
                    [
                        'name'     => 'Cheese',
                        'quantity' => 4040,
                    ],
                    [
                        'name'     => 'Onion',
                        'quantity' => 360,
                    ],
                ],
            ]);

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 10,
                ],
            ],
        ])->assertSuccessful();

        $this->getjson('/api/stock/1')
            ->assertSuccessful()
            ->assertJson([
                'data' => [
                    [
                        'name'     => 'Beef',
                        'quantity' => 15700,
                    ],
                    [
                        'name'     => 'Cheese',
                        'quantity' => 3740,
                    ],
                    [
                        'name'     => 'Onion',
                        'quantity' => 160,
                    ],
                ],
            ]);

        Mail::assertSent(LowStockIngredientMail::class, 1);

        Mail::assertSent(function (LowStockIngredientMail $mail) {
            return $mail->hasTo(config('app.admin_email')) &&
                    $mail->hasSubject('Low ingredient: Onion') &&
                    $mail->assertSeeInHtml('Ingredient Onion is low');
        });

        $this->postJson('/api/order', [
            'products' => [
                [
                    'id'       => 1,
                    'quantity' => 10,
                ],
            ],
        ])
            ->assertUnprocessable()
            ->assertJsonPath('message', 'Not enough Onion to make Burger. Wanted 200 but only 160 available.');
    }
}
