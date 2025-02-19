<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiEx extends Model
{
    use HasFactory;

    protected $table = 'kpi_ex';

    protected $fillable = [
        'assign_id',
        'data_source_id',
        'rating_scale'
    ];
}
