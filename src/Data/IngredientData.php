<?php

namespace FoodicsTest\Data;

use Spatie\LaravelData\Data;
use FoodicsTest\Models\Ingredient;

class IngredientData extends Data
{
    public function __construct(
        public string $id,
        public string $stockId,
        public string $name,
        public int $wantedQuantity,
        public int $availableQuantity,
    ) {
    }

    public static function fromModel(Ingredient $ingredient, int $wantedQuantity): self
    {
        return new self(
            $ingredient->id,
            $ingredient->stock_id,
            $ingredient->name,
            $wantedQuantity,
            $ingredient->quantity,
        );
    }
}
