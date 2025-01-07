<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GambarKaryawan extends Model
{
    use HasFactory;

    // Nama tabel yang terkait dengan model ini
    protected $table = 'gambar_karyawan';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'karyawan_id',
        'foto',
    ];

    // Definisikan relasi banyak ke satu  
    public function karyawan()
    {
        return $this->belongsTo(KaryawanBaru::class, 'karyawan_id');
    }
}
