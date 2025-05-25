<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'slug',
        'tags'
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    protected $appends = [
        'products_count'
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the products count for the category.
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }
}
