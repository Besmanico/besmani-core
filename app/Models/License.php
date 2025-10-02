<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\LicenseCheck;

class License extends Model
{
    use HasFactory;
    protected $guarded = [];
    // protected $connection = 'beauty_mysql';
    // protected $table = 'license';
    public function license_checks()
    {
        return $this->hasMany(LicenseCheck::class,'license_id');
    } 
}
