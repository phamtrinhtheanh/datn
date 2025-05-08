<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'slug',
        'tags'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            $model->slug = Str::slug($model->name) . '-c.' . $model->id;
            $model->save();
        });
    }
}
