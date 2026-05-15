<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['farm_id', 'user_id', 'name', 'type', 'quantity', 'unit', 'threshold_alert', 'low_stock_threshold'];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isLowStock()
    {
        $threshold = $this->low_stock_threshold > 0 ? $this->low_stock_threshold : $this->threshold_alert;
        return $this->quantity <= $threshold;
    }
}
