<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id', 'crop_id', 'title', 'description', 'due_date',
        'completed', 'completed_at', 'assigned_to'
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'completed' => 'boolean',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function isOverdue()
    {
        return !$this->completed && $this->due_date < now();
    }
}
