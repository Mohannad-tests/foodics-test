<?php

namespace FoodicsTest\Data;

use Spatie\LaravelData\Data;
use FoodicsTest\Models\Order;
use FoodicsTest\Models\Product;
use Spatie\LaravelData\DataCollection;

class OrderData extends Data
{
    public function __construct(
      public ?string $id,
      public int $stockId,
      public ?string $userId,
      /** @var \App\Data\ProductData[] */
      public DataCollection $products,
    ) {
    }

    public static function fromRequest(array $input, int $stockId, ?string $userId): self
    {
        // $input: [['id' => 1, 'quantity' => 2]]
        $products = ProductData::collection(
            Product::query()
                ->whereIn('id', collect($input)->pluck('id'))
                ->with('ingredients')
                ->get()
                ->map(
                    fn (Product $product) => ProductData::fromModel($product, collect($input)->firstWhere('id', $product->id)['quantity'])
                )
        );

        return new self(
            null,
            $stockId,
            $userId,
            $products,
        );
    }

    public static function fromModel(Order $order): self
    {
        return new self(
            $order->id,
            $order->stock_id,
            $order->user_id,
            ProductData::collection(
                $order->products->map(
                    fn (Product $product) => ProductData::fromModel($product, $product->pivot->quantity)
                )
            ),
        );
    }
}
