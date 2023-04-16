<?php

namespace FoodicsTest\Models;

use App\Casts\UuidCast;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * App\Models\IngredientProduct
 *
 * @property mixed|null $id
 * @property int $product_id
 * @property string $ingredient_id
 * @property int $quantity
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereIngredientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|IngredientProduct whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class IngredientProduct extends Pivot
{
    use HasUuids;

    protected $table = 'ingredient_product';

    protected $casts = [
        'id'            => UuidCast::class,
        'product_id'    => 'integer',
        'ingredient_id' => 'string',
        'quantity'      => 'integer',
        'created_at'    => 'immutable_datetime',
        'updated_at'    => 'immutable_datetime',
    ];
}
