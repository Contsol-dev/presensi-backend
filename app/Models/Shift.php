<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shifts';

    protected $fillable = [
        'nama_shift', 'masuk', 'istirahat', 'kembali', 'pulang'
    ];

    public function detailUsers()
    {
        return $this->hasMany(DetailUser::class, 'shift_id');
    }
}
