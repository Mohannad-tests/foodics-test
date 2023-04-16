<?php

namespace FoodicsTest\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class StockIngredientAdded extends ShouldBeStored
{
    public function __construct(
        public int $quantity,
    ) {
    }
}
