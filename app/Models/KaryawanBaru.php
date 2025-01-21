<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KaryawanBaru extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'karyawan_barus';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'nik',
        'nama',
        'level',
        'workplace',
        'tempat_lahir',
        'tgl_lahir',
        'tgl_masuk',
    ];
    // Definisikan relasi satu ke satu  
    public function gambarKaryawan()
    {
        return $this->hasOne(GambarKaryawan::class, 'karyawan_id');
    }

    public function posisi()  
    {  
        return $this->belongsTo(Posisi::class, 'level');  
    }  
  
    public function departemen()  
    {  
        return $this->belongsTo(Departemen::class, 'workplace');
    }
}
