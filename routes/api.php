<?php

use Illuminate\Http\Request;
use FoodicsTest\Models\Stock;
use FoodicsTest\Models\Ingredient;
use Illuminate\Support\Facades\Route;
use FoodicsTest\Actions\CreateNewOrder;
use FoodicsTest\Aggregates\StockIngredientAggregateRoot;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/stock/{stock}', function (Stock $stock) {
    $data = $stock->ingredients->map(function (Ingredient $ingredient) {
        return [
            'name'     => $ingredient->name,
            'quantity' => StockIngredientAggregateRoot::retrieve($ingredient->id)->getQuantity(),
            'low_hits' => StockIngredientAggregateRoot::retrieve($ingredient->id)->getLowIngredientHits(),
        ];
    });

    return ['data' => $data];
});

Route::post('/ingredients/{ingredient}', function (Ingredient $ingredient, Request $request) {
    $stockIngredient = StockIngredientAggregateRoot::retrieve($ingredient->id);
    $stockIngredient->add($request->quantity)->persist();

    return ['data' => $stockIngredient->getQuantity()];
});

Route::post('/order', CreateNewOrder::class);
