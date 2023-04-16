<?php

namespace FoodicsTest\Aggregates;

use FoodicsTest\Events\StockIngredientLow;
use FoodicsTest\Events\StockIngredientAdded;
use FoodicsTest\Events\StockIngredientSubtracted;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;
use FoodicsTest\Exceptions\NotEnoughIngredientException;

class StockIngredientAggregateRoot extends AggregateRoot
{
    protected int $quantity = 0;

    protected int $lowIngredientHits = 0;

    // Apply events
    public function applyStockIngredientAdded(StockIngredientAdded $event)
    {
        $this->quantity += $event->quantity;
    }

    public function applyStockIngredientSubtracted(StockIngredientSubtracted $event)
    {
        $this->quantity -= $event->quantity;
    }

    public function applyStockIngredientLow(StockIngredientLow $event)
    {
        $this->lowIngredientHits++;
    }

    // Core methods
    public function add(int $quantity)
    {
        $this->recordThat(new StockIngredientAdded($quantity));

        return $this;
    }

    public function subtract(int $quantity)
    {
        $this->recordThat(new StockIngredientSubtracted($quantity));

        return $this;
    }

    // getters and validation
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getLowIngredientHits(): int
    {
        return $this->lowIngredientHits;
    }

    public function isEnough(int $quantity): bool
    {
        return $this->quantity >= $quantity;
    }

    public function validateQuantity(int $quantity, string $ingredientName, string $productName)
    {
        if (! $this->isEnough($quantity)) {
            $availableQuantity = $this->getQuantity();

            throw NotEnoughIngredientException::notEnough(
                $ingredientName,
                $productName,
                $quantity,
                $availableQuantity
            );
        }
    }
}
