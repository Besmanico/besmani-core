<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicService extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'clinic_services';
    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'service_id');
    } 
    
}
