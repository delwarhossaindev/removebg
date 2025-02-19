<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    // Define the table name (optional if table name matches the plural form of the model name)
    protected $table = 'department';

    // Specify fillable fields for mass assignment
    protected $fillable = ['code', 'name'];
}
