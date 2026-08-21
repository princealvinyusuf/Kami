<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CertificationSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['certification_scheme_id', 'starts_at', 'ends_at', 'location', 'capacity', 'fee', 'status'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'fee' => 'decimal:2'];
    }

    public function scheme()
    {
        return $this->belongsTo(CertificationScheme::class, 'certification_scheme_id');
    }

    public function registrations()
    {
        return $this->morphMany(Registration::class, 'schedule');
    }
}
