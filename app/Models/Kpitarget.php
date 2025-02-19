<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpitarget extends Model
{
    use HasFactory;

    protected $table = 'kpi_target';

    protected $fillable = [
        'assign_id',
        'target',
    ];
}
