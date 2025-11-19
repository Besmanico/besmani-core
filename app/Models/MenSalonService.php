<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenSalonService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'man_salon_services';
    public function service()
    {
        return $this->belongsTo(MenService::class, 'service_id');
    }
}
