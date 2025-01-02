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
        'nama',
        'level',
        'departemen',
    ];
}
