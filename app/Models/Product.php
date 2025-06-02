<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
//    use Searchable;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'import_price',
        'line_price',
        'stock',
        'images',
        'specs',
        'sold',
        'rating',
        'is_featured',
        'brand_id',
        'category_id'
    ];

    protected $casts = [
        'images' => 'array',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'line_price' => $this->line_price,
            'images' => $this->getFirstImage(),
            'specs' => is_array($this->specs) ? $this->specs : json_decode($this->specs, true),
            'in_stock' => $this->stock > 0,
            'brand_name' => optional($this->brand)->name,
            'category_name' => optional($this->category)->name,
            'created_at' => $this->created_at
        ];
    }
    protected static function boot(): void
    {
        parent::boot();

        static::created(function ($product) {
            if (empty($product->slug)) {
                $product->slug = $product->name . '-p.' . $product->id;
                $product->save();
            }
        });
    }
}
