<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name_ar', 'name_en','active'];

    public function scopeSelection($query)
    {
        return $query->select('id','active', 'name_' . app()->getLocale() . ' as name');
    }
    protected $casts = [
        'active' => 'boolean',
   ];
}
