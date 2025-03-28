<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departemen extends Model
{
    use HasFactory;

    protected $table = 'departemen';
    protected $fillable = ['job_department'];

    public function karyawans()
    {
        return $this->hasMany(KaryawanBaru::class, 'workplace');
    }
}
