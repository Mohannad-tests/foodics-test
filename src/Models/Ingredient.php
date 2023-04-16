<?php

namespace FoodicsTest\Models;

use App\Casts\UuidCast;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\IngredientFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Ingredient
 *
 * @property mixed|null $id
 * @property string $stock_id
 * @property string $name
 * @property int $recommended_quantity
 * @property int $quantity
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 *
 * @method static \Database\Factories\IngredientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereRecommendedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ingredient whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Ingredient extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'ingredients';

    protected $fillable = ['name', 'quantity', 'recommended_quantity'];

    protected $casts = [
        'id'                   => UuidCast::class,
        'stock_id'             => 'string',
        'name'                 => 'string',
        'recommended_quantity' => 'integer',
        'quantity'             => 'integer',
        'created_at'           => 'immutable_datetime',
        'updated_at'           => 'immutable_datetime',
    ];

    protected static function newFactory()
    {
        return IngredientFactory::new();
    }

    public function uniqueIds()
    {
        return ['id'];
    }
}
