<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KRA extends Model
{
    protected $table = 'kras';

    protected $fillable = [
        'kra_code',
        'pm_type_id',
        'perspective_id',
        'name',
        'description',
        'status',
    ];

    public function pmType()
    {
        return $this->belongsTo(PmTypes::class, 'pm_type_id', 'id');
    }

    public function perspective()
    {
        return $this->belongsTo(Perspectives::class, 'perspective_id', 'id');
    }
}

