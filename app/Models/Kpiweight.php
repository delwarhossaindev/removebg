<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpiweight extends Model
{
    use HasFactory;

    protected $table = 'kpi_weight';

    protected $fillable = [
        'assign_id',
        'weight'
    ];
}
