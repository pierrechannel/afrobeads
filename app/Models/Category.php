<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'name',
        'image', // Assuming you may want to store the image path
    ];

    // Define the relationship with products
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
