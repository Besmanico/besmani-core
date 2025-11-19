<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortfolioServiceLink extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function portfolioService()
    {
        return $this->belongsTo(PortfolioService::class);
    }
}
