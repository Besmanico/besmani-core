<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClinicReserve extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'beauty_mysql';
    protected $table = 'clinic_reserves';
    public function clinic()
    {
        return $this->belongsTo(Clinic::class,'service_id');
    }

    public function user()
    {
        return $this->belongsTo(MainUser::class, 'personnel_id');
    }
    
}
