<?php

namespace App\Models;

use App\Models\TrainingCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WomanTraining extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_learning';
    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'category_id');
    }
} 
