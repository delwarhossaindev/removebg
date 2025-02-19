<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PmTypes extends Model
{
    protected $table = 'pm_types';

    protected $fillable = [
        'name',
    ];
}
