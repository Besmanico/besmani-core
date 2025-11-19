<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioService extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function portfolioServiceLinks()
    {
        return $this->hasMany(PortfolioServiceLink::class);
    }
}
