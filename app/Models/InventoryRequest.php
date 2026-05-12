<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $fillable = ['farmer_id', 'item_id', 'quantity', 'status', 'notes'];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }
}
