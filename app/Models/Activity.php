<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\InfoActivity;

class Activity extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'activity';   

    public function providers()
    {
        return $this->hasMany(InfoActivity::class, 'activity_id');
    } 

}
