<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FarmerRequest extends Model
{
    protected $fillable = [
        'farmer_id',
        'request_type',
        'item_name',
        'quantity',
        'description',
        'status',
        'admin_response'
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }
}
