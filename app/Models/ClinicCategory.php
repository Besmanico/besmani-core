<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'clinic_category'; 
}
