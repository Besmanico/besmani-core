<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class manTraining extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_manlearning';
    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'category_id');
    }
}
