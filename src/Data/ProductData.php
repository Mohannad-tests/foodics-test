<?php

namespace FoodicsTest\Data;

use Spatie\LaravelData\Data;
use FoodicsTest\Models\Product;
use FoodicsTest\Models\Ingredient;
use Spatie\LaravelData\DataCollection;

class ProductData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public int $orderQuantity,
        /** @var \App\Data\IngredientData[] */
        public DataCollection $ingredients,
    ) {
    }

    public static function fromModel(Product $product, int $orderQuantity): self
    {
        return new self(
            $product->id,
            $product->name,
            $orderQuantity,
            IngredientData::collection(
                $product->ingredients->map(
                    fn (Ingredient $ingredient) => IngredientData::fromModel($ingredient, $ingredient->pivot->quantity * $orderQuantity)
                )
            ),
        );
    }
}
