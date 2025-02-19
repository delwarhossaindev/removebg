<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kpis extends Model
{
    use HasFactory;

    protected $table = 'kpis';

    protected $fillable = [
        'name',
        'status',
    ];
}

