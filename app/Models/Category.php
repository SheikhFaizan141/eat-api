<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    use HasFactory;


    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }


    public function scopeSorted(Builder $query, string $sortField = 'created_at', string $sortOrder = 'desc'): Builder
    {
         // Define allowed sort fields
        $allowedFields = ['id', 'name', 'created_at'];
        // Ensure $sortOrder is either 'asc' or 'desc' to avoid SQL injection risk
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
    
        $sortField = in_array($sortField, $allowedFields) ? $sortField : 'created_at';
    
        return $query->orderBy($sortField, $sortOrder);
    }
}
