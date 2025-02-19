<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreAreaSub extends Model
{
    use HasFactory;

    protected $table = 'core_areas_sub';

    protected $fillable = [
        'core_area_id', 'name', 'status', 'created_by', 'updated_by'
    ];

    public function coreArea()
    {
        return $this->belongsTo(CoreArea::class, 'core_area_id');
    }
}
