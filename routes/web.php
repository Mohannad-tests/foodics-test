<?php

use FoodicsTest\Models\Order;
use FoodicsTest\Models\Stock;
use FoodicsTest\Models\Product;
use FoodicsTest\Models\Ingredient;
use FoodicsTest\Models\OrderProduct;
use FoodicsTest\Models\ProductStock;
use Illuminate\Support\Facades\Route;
use FoodicsTest\Models\IngredientProduct;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $stocks = Stock::all();
    $products = Product::all();
    $ingredients = Ingredient::all();
    $orders = Order::all();
    $productStock = ProductStock::all();
    $ingredientProduct = IngredientProduct::all();
    $orderProduct = OrderProduct::all();

    return view('welcome', [
        'stocks'            => $stocks,
        'products'          => $products,
        'ingredients'       => $ingredients,
        'orders'            => $orders,
        'productStock'      => $productStock,
        'ingredientProduct' => $ingredientProduct,
        'orderProduct'      => $orderProduct,
    ]);
});
