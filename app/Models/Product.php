<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price',
        'cost_price',
        'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
