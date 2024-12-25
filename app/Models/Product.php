<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'category_id',
        'name',
        'price',
        'image', // Assuming you may want to store the image path
        'stock',
    ];

    // Define the relationship with category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
