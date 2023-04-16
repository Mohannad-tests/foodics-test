<?php

namespace FoodicsTest\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class OrderCreated extends ShouldBeStored
{
    public function __construct(
        public string $id,
    ) {
    }
}
