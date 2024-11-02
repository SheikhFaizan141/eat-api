<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price'];

    // Hide image_path from JSON responses
    protected $hidden = ['image_path'];


    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    // Scope for sorting products
    public function scopeSorted($query, $sortField = 'created_at', $sortOrder = 'desc')
    {
        return $query->orderBy($sortField, $sortOrder);
    }
}
