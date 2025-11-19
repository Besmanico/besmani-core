<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WomenAcademyCourse extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'info_course';
    public function service()
    {
        return $this->belongsTo(WomenAcademyService::class, 'course_id');
    }
}
