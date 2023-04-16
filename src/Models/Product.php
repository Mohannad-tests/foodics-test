<?php

namespace FoodicsTest\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ingredient> $ingredients
 * @property-read int|null $ingredients_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Stock> $stocks
 * @property-read int|null $stocks_count
 *
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = ['name'];

    protected $casts = [
        'id'         => 'integer',
        'name'       => 'string',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class)
                    ->withTimestamps();
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class)
                    ->using(IngredientProduct::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
