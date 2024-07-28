<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $table = 'detail_users';

    protected $fillable = [
        'username',
        'nama', 
        'asal_sekolah', 
        'tempat_lahir', 
        'tanggal_lahir', 
        'nomor_hp', 
        'nip', 
        'status_pegawai', 
        'tanggal_masuk', 
        'tanggal_keluar', 
        'divisi_id', 
        'shift_id', 
        'nilai_id'
    ];

    protected $primaryKey = 'username';
    public $incrementing = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'username', 'username');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'divisi_id');
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }
}
