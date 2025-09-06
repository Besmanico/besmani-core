<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MainUser;

class Product extends Model
{
    use HasFactory;
    protected $connection = 'beauty_mysql';
    protected $table = 'tbl_products';

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class,'category_id');
    }
    public function user()
    {
        return $this->belongsTo(MainUser::class,'user_id');
    }
}
