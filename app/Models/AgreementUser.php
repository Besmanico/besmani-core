<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgreementUser extends Model
{
    use HasFactory;

    protected $guarded = []; 
    public function agreement_category()
    {
        return $this->belongsTo(AgreementCategory::class);
    } 
    public function user()
    {
        return $this->belongsTo(User::class);
    } 


}
