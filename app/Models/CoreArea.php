<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreArea extends Model
{
    use HasFactory;

    protected $table = 'core_areas';

    protected $fillable = [
        'name', 'frequency_id', 'year', 'status', 'business', 'created_by', 'updated_by'
    ];

    public function coreAreaSubs()
    {
        return $this->hasMany(CoreAreaSub::class, 'core_area_id');
    }

    public function frequencyInfo()
    {
        return $this->belongsTo(Frequencies::class, 'frequency_id', 'id');
    }
}
