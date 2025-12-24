<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_manservices';
    public function category()
    {
        return $this->belongsTo(WomenServiceCategory::class, 'category_id');
    }
}
