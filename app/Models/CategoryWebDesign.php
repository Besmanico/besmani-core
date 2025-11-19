<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryWebDesign extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function categoryWebDesignItems()
    {
        return $this->hasMany(CategoryWebDesignItem::class);
    }
    public function portfolioService()
    {
        return $this->hasMany(PortfolioService::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function clientProjects()
    {
        return $this->hasMany(ClientProject::class);
    }
}
