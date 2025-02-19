<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpiassin extends Model
{
    use HasFactory;

    protected $table = 'assign_kpis';

    protected $fillable = [
        'user_id',
        'kpi_id',
        'period',
        'frequency_id',
        'data_source_id',
        'rating_scale',
        'created_by',
        'updated_by'
    ];

    public function weight()
    {
        return $this->hasOne(Kpiweight::class, 'assign_id');
    }

    public function kpiex()
    {
        return $this->hasOne(KpiEx::class, 'assign_id');
    }

    public function target()
    {
        return $this->hasMany(Kpitarget::class, 'assign_id');
    }

    public function actual()
    {
        return $this->hasMany(Kpiactual::class, 'assign_id');
    }

    /**
     * Relationship to the KpiMain model
     */
    public function kpis()
    {
        return $this->belongsTo(KpiMains::class, 'kpi_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function frequencies()
    {
        return $this->hasOne(Frequencies::class, 'id', 'frequency_id');
    }


    /**
     * Relationship to the Perspectives model through KpiMain
     */
    public function perspective()
    {
        return $this->hasOneThrough(
            Perspectives::class,
            KpiMains::class,
            'id',                // Foreign key on KpiMain table
            'id',                // Foreign key on Perspectives table
            'kpi_id',       // Local key on Kpiassin table
            'perspective_id'     // Local key on KpiMain table
        );
    }

    /**
     * Relationship to the Kra model through KpiMain
     */
    public function kra()
    {
        return $this->hasOneThrough(
            Kra::class,
            KpiMains::class,
            'id',                // Foreign key on KpiMain table
            'id',                // Foreign key on Kra table
            'kpi_id',       // Local key on Kpiassin table
            'kra_id'             // Local key on KpiMain table
        );
    }

    public function objective()
    {
        return $this->hasOneThrough(
            Objectives::class,
            KpiMain::class,
            'id',                // Foreign key on KpiMain table
            'id',                // Foreign key on Kra table
            'kpi_main_id',       // Local key on Kpiassin table
            'objective_id'             // Local key on KpiMain table
        );
    }

    public function unitInfo()
    {
        return $this->hasOneThrough(
            Units::class,
            KpiMain::class,
            'id',                // Foreign key on KpiMain table
            'id',                // Foreign key on Kra table
            'kpi_main_id',       // Local key on Kpiassin table
            'unit_id'             // Local key on KpiMain table
        );
    }



}
