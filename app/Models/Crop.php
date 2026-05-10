<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id', 'name', 'variety', 'planting_date', 'expected_harvest_date',
        'actual_harvest_date', 'yield_kg', 'status'
    ];

    protected $casts = [
        'planting_date' => 'date',
        'expected_harvest_date' => 'date',
        'actual_harvest_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class);
    }

    public function daysRemaining()
    {
        if ($this->status !== 'growing') return 0;
        return max(0, now()->diffInDays($this->expected_harvest_date, false));
    }

    public function progressPercentage()
    {
        if ($this->status === 'harvested') return 100;
        if ($this->status === 'failed') return 0;

        $totalDays = $this->planting_date->diffInDays($this->expected_harvest_date);
        if ($totalDays <= 0) return 100;

        $daysPassed = $this->planting_date->diffInDays(now(), false);
        
        $percentage = ($daysPassed / $totalDays) * 100;
        
        return max(0, min(100, round($percentage)));
    }
}
