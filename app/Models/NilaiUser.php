<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiUser extends Model
{
    use HasFactory;

    protected $fillable = ['username', 'category_id', 'subcategory_id', 'nilai'];

    public function category()
    {
        return $this->belongsTo(NilaiCategory::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(NilaiSubcategory::class, 'subcategory_id');
    }
}
