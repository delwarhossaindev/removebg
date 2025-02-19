<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpiactual extends Model
{
    use HasFactory;

    protected $table = 'kpi_actual';

    protected $fillable = [
        'assign_id',
        'actual',
        'remarks',
        'files'

    ];
}
