<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $fillable = [
        'nama_divisi',
    ];

    public function detailUsers()
    {
        return $this->hasMany(DetailUser::class, 'divisi_id');
    }
}
