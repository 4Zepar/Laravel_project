<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'category_id', 'specs'];

    protected $casts = [
        'specs' => 'array', 
    ];

    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'product_user');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
    public function comments() {
        return $this->hasMany(Comment::class)->whereNull('parent_id')->latest();
    }
}
