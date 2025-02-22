<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSource extends Model
{
    use HasFactory;

    protected $table = 'data_sources';

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'updated_by'
    ];
}
