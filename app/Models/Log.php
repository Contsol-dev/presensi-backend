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
        'user_id',
        'tanggal',
        'masuk',
        'istirahat',
        'kembali',
        'pulang',
        'log_activity',
        'kebaikan'
    ];
}
