<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'registration_type',
        'registrable_type',
        'registrable_id',
        'schedule_type',
        'schedule_id',
        'name',
        'email',
        'phone',
        'city',
        'province',
        'education',
        'institution',
        'occupation',
        'preferred_schedule',
        'nik',
        'gender',
        'birth_date',
        'notes',
        'document_path',
        'document_name',
        'document_mime',
        'document_size',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'document_size' => 'integer',
            'status' => RegistrationStatus::class,
            'metadata' => 'array',
        ];
    }

    public function registrable()
    {
        return $this->morphTo();
    }

    public function schedule()
    {
        return $this->morphTo();
    }
}
