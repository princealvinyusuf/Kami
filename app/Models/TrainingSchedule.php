<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['training_program_id', 'starts_at', 'ends_at', 'location', 'capacity', 'price', 'status'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'price' => 'decimal:2'];
    }

    public function program()
    {
        return $this->belongsTo(TrainingProgram::class, 'training_program_id');
    }

    public function registrations()
    {
        return $this->morphMany(Registration::class, 'schedule');
    }
}
