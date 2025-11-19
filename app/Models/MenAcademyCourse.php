<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenAcademyCourse extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'info_man_course'; 
    public function service()
    {
        return $this->belongsTo(MenAcademyService::class, 'course_id');
    }
}
