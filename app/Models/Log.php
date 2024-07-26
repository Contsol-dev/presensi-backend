<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $table = 'logs';
    protected $primaryKey = 'log_id';


    protected $fillable = [
        'username',
        'tanggal',
        'masuk',
        'istirahat',
        'kembali',
        'pulang',
        'log_activity',
        'kebaikan',
        'terlambat_masuk',
        'istirahat_awal',
        'terlambat_kembali',
        'pulang_awal',
        'kehadiran'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }
}
