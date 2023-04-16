<?php

namespace FoodicsTest\Actions;

use FoodicsTest\Models\Order;
use FoodicsTest\Data\OrderData;
use FoodicsTest\Data\ProductData;
use Illuminate\Support\Facades\DB;
use Database\Seeders\DatabaseSeeder;
use FoodicsTest\Data\IngredientData;
use FoodicsTest\Events\OrderCreated;
use FoodicsTest\Http\Requests\CreateNewOrderRequest;
use FoodicsTest\Aggregates\StockIngredientAggregateRoot;

class CreateNewOrder
{
    public function __invoke(CreateNewOrderRequest $request): array
    {
        // products input: [['id' => 1, 'quantity' => 2]]
        $products = collect($request->validated()['products']);

        // check available ingredients quantities
        $orderData = OrderData::fromRequest(
            $request->validated()['products'],
            DatabaseSeeder::DEFAULT_STOCK_ID,
            null
        );
        $orderData->products->each(
            fn (ProductData $product) => $product->ingredients->each(
                fn (IngredientData $ingredient) => StockIngredientAggregateRoot::retrieve($ingredient->id)
                        ->validateQuantity(
                            $ingredient->wantedQuantity,
                            $ingredient->name,
                            $product->name,
                        )
            )
        );

        // store the order
        $order = DB::transaction(function () use ($products) {
            /*** @var Order $order */
            $order = Order::create(['stock_id' => DatabaseSeeder::DEFAULT_STOCK_ID]);

            $order->products()->attach($products->mapWithKeys(function ($product) {
                return [$product['id'] => ['quantity' => $product['quantity']]];
            })->toArray());

            return $order;
        });

        event(new OrderCreated($order->id));

        return [
            'data' => $order->load('products'),
        ];
    }
}
