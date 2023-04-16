<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use FoodicsTest\Models\Stock;
use Illuminate\Database\Seeder;
use FoodicsTest\Models\Ingredient;
use FoodicsTest\Aggregates\StockIngredientAggregateRoot;

class DatabaseSeeder extends Seeder
{
    const DEFAULT_STOCK_ID = 1;

    const DEFAULT_INGREDIENTS = [
        'Beef' => [
            'quantity' => 22 * 1000,
        ],
        'Cheese' => [
            'quantity' => 5 * 1000,
        ],
        'Onion' => [
            'quantity' => 1 * 1000,
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         $stock = Stock::query()->firstOrCreate([
             'id' => self::DEFAULT_STOCK_ID,
         ]);

         $burger = $stock->products()->firstOrCreate([
             'name' => 'Burger',
         ]);

        $beef = $stock->ingredients()->firstOrCreate([
            'name'                 => 'Beef',
            'quantity'             => self::DEFAULT_INGREDIENTS['Beef']['quantity'],
            'recommended_quantity' => self::DEFAULT_INGREDIENTS['Beef']['quantity'] / 2,
        ]);
        $cheese = $stock->ingredients()->firstOrCreate([
            'name'                 => 'Cheese',
            'quantity'             => self::DEFAULT_INGREDIENTS['Cheese']['quantity'],
            'recommended_quantity' => self::DEFAULT_INGREDIENTS['Cheese']['quantity'] / 2,
        ]);
        $onion = $stock->ingredients()->firstOrCreate([
            'name'                 => 'Onion',
            'quantity'             => self::DEFAULT_INGREDIENTS['Onion']['quantity'],
            'recommended_quantity' => self::DEFAULT_INGREDIENTS['Onion']['quantity'] / 2,
        ]);

        $burger->ingredients()->syncWithoutDetaching([
            $beef->id   => ['quantity' => 150],
            $cheese->id => ['quantity' => 30],
            $onion->id  => ['quantity' => 20],
        ]);

        $stock->ingredients->each(function (Ingredient $ingredient) {
            StockIngredientAggregateRoot::retrieve($ingredient->id)
                ->add($ingredient->quantity)
                ->persist();
        });
    }
}
