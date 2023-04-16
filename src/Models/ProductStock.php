<?php

namespace FoodicsTest\Models;

use App\Casts\UuidCast;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * App\Models\ProductStock
 *
 * @property mixed|null $id
 * @property int $product_id
 * @property int $stock_id
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property-read \App\Models\Product $product
 * @property-read \App\Models\Stock $stock
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereStockId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductStock whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
class ProductStock extends Pivot
{
    use HasUuids;

    protected $table = 'product_stock';

    protected $casts = [
        'id'         => UuidCast::class,
        'product_id' => 'integer',
        'stock_id'   => 'integer',
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    protected function castAttribute($key, $value)
    {
        // if ($this->getCastType($key) === 'string') {
        //     dump(['here1', $key, $value]);
        //     return is_null($value) ? $value : dd($value);
        // }
        //     dump(['here2', $key, $value]);

        return parent::castAttribute($key, $value);
    }

    public function uniqueIds()
    {
        return ['id'];
    }
}
