<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $casts = [
        'keywords' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function parent()
    {
        return $this->belongsTo(MarketCategory::class, 'parent_id');
    }
}
