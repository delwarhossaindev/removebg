<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'kpi_main_id',
        'target',
        'weight',
        'type',
    ];

    public function kpimainInfo()
    {
        return $this->belongsTo(KpiMains::class, 'kpi_main_id', 'id');
    }
}

