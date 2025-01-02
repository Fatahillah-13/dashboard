<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryawanBaru;

class KaryawanBaruController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => '',
            'level' => '',
            'departemen' => '',
        ]);

        KaryawanBaru::create($validatedData);

        return redirect()->back()->with('success', 'Data karyawan baru berhasil ditambahkan.');
    }
}
