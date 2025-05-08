<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'import_price',
        'line_price',
        'stock',
        'image',
        'specs',
        'is_featured',
        'brand_id',
        'category_id'
    ];
    protected $casts = [
        'images' => 'array',
    ];


    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name) . '-p.' . $product->id;;
        });


        static::updating(function ($product) {
            $product->slug = Str::slug($product->name) . '-p.' . $product->id;
        });

    }

    public function searchableAs()
    {
        return 'products';  // Tên index Meilisearch
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

    public function getFirstImage()
    {
        $images = json_decode($this->images, true);

        return is_array($images) && count($images) > 0 ? $images[0] : null;
    }

}
