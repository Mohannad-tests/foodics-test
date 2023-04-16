<?php

namespace FoodicsTest\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotEnoughIngredientException extends ValidationException
{
    public static function notEnough(string $ingredientName, string $productName, int $quantity, int $availableQuantity)
    {
        return static::withMessages([
            'ingredients' => "Not enough {$ingredientName} to make {$productName}. Wanted {$quantity} but only {$availableQuantity} available.",
        ]);
    }

    public function render(Request $request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors'  => $this->errors(),
        ], $this->status);
    }
}
