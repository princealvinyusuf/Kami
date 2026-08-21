<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompetencyUnit extends Model
{
    use SoftDeletes;

    protected $fillable = ['certification_scheme_id', 'code', 'title', 'description', 'sort_order'];

    public function scheme()
    {
        return $this->belongsTo(CertificationScheme::class, 'certification_scheme_id');
    }
}
