<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiSubcategory extends Model
{
    use HasFactory;

    protected $fillable = ['nama_subkategori', 'category_id'];

    public function category()
    {
        return $this->belongsTo(NilaiCategory::class, 'category_id');
    }

    public function users()
    {
        return $this->hasMany(NilaiUser::class, 'subcategory_id');
    }
}
