<?php

namespace FoodicsTest\Projectors;

use FoodicsTest\Models\Order;
use FoodicsTest\Data\OrderData;
use FoodicsTest\Data\ProductData;
use FoodicsTest\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use FoodicsTest\Data\IngredientData;
use FoodicsTest\Events\OrderCreated;
use FoodicsTest\Events\StockIngredientLow;
use Illuminate\Contracts\Queue\ShouldQueue;
use FoodicsTest\Events\StockIngredientAdded;
use FoodicsTest\Events\StockIngredientSubtracted;
use FoodicsTest\Aggregates\StockIngredientAggregateRoot;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class StocksProjector extends Projector implements ShouldQueue
{
    public function onOrderCreated(OrderCreated $event)
    {
        $order = Order::query()
            ->with('stock.ingredients')
            ->with('products.ingredients')
            ->find($event->id);

        $orderData = OrderData::fromModel($order);

        $orderData->products->each(
            fn (ProductData $product) => $product->ingredients->each(
                fn (IngredientData $ingredient) => StockIngredientAggregateRoot::retrieve($ingredient->id)
                        ->subtract($ingredient->wantedQuantity)
                        ->persist()
                )
        );
    }

    public function onStockIngredientSubtracted(StockIngredientSubtracted $event)
    {
        $ingredientId = $event->aggregateRootUuid();

        $this->checkLowIngredient($ingredientId);

        $this->syncIngredientQuantity($ingredientId);
    }

    public function onStockIngredientAdded(StockIngredientAdded $event)
    {
        $ingredientId = $event->aggregateRootUuid();

        $this->syncIngredientQuantity($ingredientId);
    }

    protected function checkLowIngredient(string $ingredientId)
    {
        $recommendedQuantity = Ingredient::query()
            ->where('id', $ingredientId)
            ->value('recommended_quantity');

        $aggregateRoot = StockIngredientAggregateRoot::retrieve($ingredientId);

        if ($aggregateRoot->getQuantity() <= $recommendedQuantity) {
            $aggregateRoot->recordThat(new StockIngredientLow())
                ->persist();
        }
    }

    protected function syncIngredientQuantity(string $ingredientId)
    {
        DB::transaction(function () use ($ingredientId) {
            $ingredientModel = Ingredient::query()
                ->lockForUpdate()
                ->find($ingredientId);

            $ingredientModel
                ->update(['quantity' => StockIngredientAggregateRoot::retrieve($ingredientId)->getQuantity()]);
        });
    }
}
