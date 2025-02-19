<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpiMains extends Model
{
    use HasFactory;

    protected $table = 'kpis';

    protected $fillable = [
        'name',
        'objective_id',
        'perspective_id',
        'unit_id',
        'frequency_id',
        'type',
        'pm_type_id',
        'kra_id',
        'aggregation_types_id',
        'co_relations_id',
        'department_id',
        'business',
        'kpi_code',
        'directions_id',
        'department_id',
        'directions_id',
        'category_id',
        'sub_department_id',
        'created_by',
        'updated_by'
    ];

    public function kpiInfo()
    {
        return $this->belongsTo(Kpis::class, 'kpi_id', 'id');
    }

    // public function weight()
    // {
    //     return $this->belongsTo(Kpiweight::class, 'id', 'kpi_main_id');
    // }


    public function objectiveInfo()
    {
        return $this->belongsTo(Objectives::class, 'objective_id', 'id');
    }

    public function perspectiveInfo()
    {
        return $this->belongsTo(Perspectives::class, 'perspective_id', 'id');
    }

    public function unitInfo()
    {
        return $this->belongsTo(Units::class, 'unit_id', 'id');
    }

    public function frequencyInfo()
    {
        return $this->belongsTo(Frequencies::class, 'frequency_id', 'id');
    }

    public function pmtypes()
    {
        return $this->belongsTo(PmTypes::class, 'pm_type_id', 'id');
    }

    public function kras()
    {
        return $this->belongsTo(KRA::class, 'kra_id', 'id');
    }

    public function aggregationtypes()
    {
        return $this->belongsTo(KpiMainAggregationType::class, 'aggregation_types_id', 'id');
    }

    public function corelations()
    {
        return $this->belongsTo(KpiMainCoRelation::class, 'co_relations_id', 'id');
    }

    public function preformance_drivers()
    {
        return $this->hasMany(KpimainPerformancedrivers::class, 'kpi_id', 'id');
    }


}




