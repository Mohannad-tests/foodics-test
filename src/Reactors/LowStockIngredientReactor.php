<?php

namespace FoodicsTest\Reactors;

use FoodicsTest\Models\Ingredient;
use Illuminate\Support\Facades\Mail;
use FoodicsTest\Events\StockIngredientLow;
use Illuminate\Contracts\Queue\ShouldQueue;
use FoodicsTest\Mail\LowStockIngredientMail;
use FoodicsTest\Aggregates\StockIngredientAggregateRoot;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class LowStockIngredientReactor extends Reactor implements ShouldQueue
{
    public function onStockIngredientLow(StockIngredientLow $event)
    {
        $ingredient = Ingredient::query()
            ->where('id', $event->aggregateRootUuid())
            ->first();

        $lowIngredientHits = StockIngredientAggregateRoot::retrieve($event->aggregateRootUuid())->getLowIngredientHits();

        if ($lowIngredientHits === 1) {
            Mail::to(config('app.admin_email'))->send(new LowStockIngredientMail($ingredient));
        }
    }
}
