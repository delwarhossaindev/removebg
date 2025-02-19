<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequencies extends Model
{
    use HasFactory;

    protected $table = 'frequencies';

    protected $fillable = [
        'name',
        'no_of_month',
        'status',
    ];
}

