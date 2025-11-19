<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;


    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_clinic';

    public function category()
    {
        return $this->belongsTo(ClinicCategory::class,'category_id');
    }

}
