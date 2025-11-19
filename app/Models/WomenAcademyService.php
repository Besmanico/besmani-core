<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WomenAcademyService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_learning';
    // public function service()
    // {
    //     return $this->belongsTo(WomenService::class, 'service_id');
    // }
}
