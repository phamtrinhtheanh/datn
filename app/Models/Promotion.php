<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'max_uses',
        'used_count',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2'
    ];

    public function isValid()
    {
        return $this->is_active &&
            now()->between($this->start_date, $this->end_date) &&
            ($this->max_uses === null || $this->used_count < $this->max_uses);
    }

    public function calculateDiscount($amount)
    {
        if (!$this->isValid() || $amount < $this->min_order_amount) {
            return 0;
        }

        if ($this->type === 'percentage') {
            return ($amount * $this->value) / 100;
        }

        return $this->value;
    }
}
