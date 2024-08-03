<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiCategory extends Model
{
    use HasFactory;

    protected $fillable = ['nama_kategori', 'division_id'];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function subcategories()
    {
        return $this->hasMany(NilaiSubcategory::class, 'category_id');
    }

    public function users()
    {
        return $this->hasMany(NilaiUser::class, 'category_id');
    }
}
