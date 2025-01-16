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
        $karyawans = KaryawanBaru::with('gambarKaryawan')->select('karyawan_barus.*'); // Ambil semua data pengguna
        return datatables()->of($karyawans) // Menggunakan DataTables
            ->addColumn('no_foto', function ($gambar) {
                return $gambar->gambarKaryawan ? $gambar->gambarKaryawan->no_foto : 0; // Menampilkan nama karyawan  
            })
            ->addColumn('foto', function ($karyawan) {
                if ($karyawan->gambarKaryawan && $karyawan->gambarKaryawan->foto) {
                    $fotoPath = asset('storage/' . $karyawan->gambarKaryawan->foto);
                    $fotoTitle = $karyawan->gambarKaryawan->foto;
                    return '<button class="btn btn-primary foto-btn" data-foto-path="' . $fotoPath . '" data-foto-title="' . $fotoTitle . '">' . $fotoTitle . '</button>';
                }
                return 'Tidak ada foto';
            })
            ->addColumn('created_at', function ($gambar) {
                return $gambar->created_at->format('Y-m-d H:i:s'); // Format tanggal  
            })
            ->addColumn('updated_at', function ($gambar) {
                return $gambar->gambarKaryawan ? $gambar->gambarKaryawan->updated_at->format('Y-m-d H:i:s') : 'Belum Ada Foto'; // Format tanggal  
            })
            ->addColumn('action', function ($karyawan) {
                $editButton = '<button class="btn btn-primary mt-1 mr-1 btn-sm edit" data-id="' . $karyawan->id . '">Edit</button>';
                $deleteButton = '<button class="btn btn-danger mt-1 btn-sm delete" data-id="' . $karyawan->id . '">Delete</button>';

                if ($karyawan->gambarKaryawan && $karyawan->gambarKaryawan->foto) {
                    return $editButton . $deleteButton;
                } else {
                    $takePictureButton = '<button class="btn btn-success mt-1 btn-sm foto" data-id="' . $karyawan->id . '">Take Picture</button>';
                    return $editButton . $deleteButton . $takePictureButton;
                }
            })
            ->rawColumns(['foto', 'action'])
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
    }

    public function getPhotoList()
    {

        $karyawans = GambarKaryawan::with('karyawan')->select('gambar_karyawan.*'); // Ambil semua data pengguna
        return datatables()->of($karyawans) // Menggunakan DataTables
            ->addColumn('karyawan_id', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->nama : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('karyawan_position', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->level : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('karyawan_departemen', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->departemen : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('created_at', function ($gambar) {
                return $gambar->created_at->format('Y-m-d H:i:s'); // Format tanggal  
            })
            ->addColumn('updated_at', function ($gambar) {
                return $gambar->updated_at->format('Y-m-d H:i:s'); // Format tanggal  
            })
            ->addColumn('action', function ($karyawans) {
                return '<button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Edit</button>
                        <button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Take Picture</button>
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
            'no_foto' => $request->input('no_foto'),
            'foto' => $imageName,
        ]);

        return response()->json(['message' => 'Image saved successfully']);
    }
}
