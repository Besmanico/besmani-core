<?php

namespace App\Models;

use App\Models\WomenService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WomenServiceSalon extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'salon_services';
    public function service()
    {
        return $this->belongsTo(WomenService::class, 'service_id');
    } 
}
