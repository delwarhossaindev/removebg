<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiActivities extends Model
{
    use HasFactory;

    protected $fillable = [
        'periofre',
        'frequency_id',
        'user_id',
        'kpi_main_id',
        'target',
        'weight',
        'achievement',
        'score',
    ];

    public function frequencyInfo()
    {
        return $this->belongsTo(Frequencies::class, 'frequency_id', 'id');
    }

    public function kpimainInfo()
    {
        return $this->belongsTo(KpiMains::class, 'kpi_main_id', 'id');
    }


}

