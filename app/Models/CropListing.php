<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CropListing extends Model
{
    protected $fillable = [
        'crop_id',
        'farmer_id',
        'quantity_available',
        'price_per_unit',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
