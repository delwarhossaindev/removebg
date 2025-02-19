<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpimainPerformancedrivers extends Model
{
    use HasFactory;

    protected $table = 'performance_drivers';

    protected $fillable = [
        'kpi_id',
        'name',
    ];
}
