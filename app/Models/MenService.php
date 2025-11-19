<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_manservices';
    // public function category()
    // {
    //     return $this->belongsTo(MenServiceCategory::class, 'category_id');
    // }
}
