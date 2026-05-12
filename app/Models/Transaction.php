<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['buyer_id', 'crop_id', 'quantity', 'total_price'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }
}
