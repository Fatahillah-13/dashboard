<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Posisi extends Model
{
    use HasFactory;
    
    protected $table = 'posisi';  
    protected $fillable = ['level'];  

    public function karyawans()
    {
        return $this->hasMany(KaryawanBaru::class, 'level');
    }
}
