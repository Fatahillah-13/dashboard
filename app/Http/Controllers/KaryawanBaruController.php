<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryawanBaru;
use App\Models\GambarKaryawan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class KaryawanBaruController extends Controller
{
    public function index()
    {
        return view('list_new_member'); // Ganti dengan nama view Anda
    }

    public function getUsers()
    {
        $karyawans = KaryawanBaru::all(); // Ambil semua data pengguna
        return datatables()->of($karyawans) // Menggunakan DataTables
            ->addColumn('action', function ($karyawans) {
                return '<button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $karyawans->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true); // Menggunakan DataTables
    }

    public function show($id)
    {
        return KaryawanBaru::findOrFail($id);
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'departemen' => 'required|string|max:255',
        ]);
        $karyawans = KaryawanBaru::find($id);
        $karyawans->update($request->all());
        return response()->json(['success' => 'User updated successfully.']);
    }

    public function destroy($id)
    {
        KaryawanBaru::destroy($id);
        return response()->json(['success' => 'User deleted successfully.']);
    }

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

    public function getPhotoList()
    {
       
        $karyawans = GambarKaryawan::all(); // Ambil semua data pengguna
        return datatables()->of($karyawans) // Menggunakan DataTables
            ->addColumn('action', function ($karyawans) {
                return '<button class="btn btn-success btn-sm edit" data-id="' . $karyawans->id . '">Foto</button>
                        <button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $karyawans->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true); // Menggunakan DataTables
    }
    public function storeFoto(Request $request)
    {
        $image = $request->input('image'); // Ambil data gambar dari request
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'pic_' . time() . '.jpeg';

        file_put_contents(public_path('storage/' . $imageName), base64_decode($image));

        // Simpan data ke database
        // $capture = new GambarKaryawan();
        // $capture->karyawan_id = $request->input('karyawan_id');
        // $capture->image = $imageName;
        // $capture->save();

        GambarKaryawan::create([
            'karyawan_id' => $request->input('karyawan_id'),
            'foto' => $imageName,
        ]);

        return response()->json(['message' => 'Image saved successfully']);
    }
}
