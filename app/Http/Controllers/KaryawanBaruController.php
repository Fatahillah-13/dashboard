<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryawanBaru;
use App\Models\GambarKaryawan;
use App\Models\Posisi;
use App\Models\Departemen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KaryawanBaruController extends Controller
{
    // public function index()
    // {
    //     $posisis = Posisi::all();
    //     $departemens = Departemen::all();
    //     return view('list_new_member')->with('posisis', $posisis)->with('departemens', $departemens);;
    // }
    public function index()
    {
        $karyawans = KaryawanBaru::with(['gambarkaryawan', 'posisi', 'departemen'])->get(); // Mengambil data karyawan beserta gambarnya, level, dan departemen
        return view('candidatelist', compact('karyawans'));
    }

    public function getUsers()
    {
        $karyawans = KaryawanBaru::with('gambarKaryawan', 'posisi', 'departemen')->select('karyawan_barus.*'); // Ambil semua data pengguna
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
            ->addColumn('level', function ($karyawan) {
                return $karyawan->posisi ? $karyawan->posisi->level : 'Tidak ada posisi';
            })
            ->addColumn('workplace', function ($karyawan) {
                return $karyawan->departemen ? $karyawan->departemen->workplace : 'Tidak ada departemen';
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
        return KaryawanBaru::with('gambarKaryawan', 'posisi', 'departemen')->findOrFail($id);
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'level' => 'required|exists:posisi,id',
            'workplace' => 'required|exists:departemen,id',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'tgl_masuk' => 'required|date',
        ]);
        $karyawans = KaryawanBaru::findOrFail($id);
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
            'nik' => '',
            'nama' => '',
            'level' => '',
            'workplace' => '',
            'tempat_lahir' => '',
            'tgl_lahir' => '',
            'tgl_masuk' => '',
        ]);

        $karyawan = KaryawanBaru::create($validatedData);

        // Tangani upload gambar jika ada
        if ($request->has('foto')) {
            $this->storeFoto($request, $karyawan->id); // Pass the karyawan ID to storeFoto
        }
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
    // public function storeFoto(Request $request, $karyawanId)
    // {
    //     $image = $request->input('image'); // Ambil data gambar dari request
    //     $image = str_replace('data:image/jpeg;base64,', '', $image);
    //     $image = str_replace(' ', '+', $image);
    //     $imageName = 'pic_' . time() . '.jpeg';

    //     file_put_contents(public_path('storage/' . $imageName), base64_decode($image));

    //     // Simpan data ke database
    //     // $capture = new GambarKaryawan();
    //     // $capture->karyawan_id = $request->input('karyawan_id');
    //     // $capture->image = $imageName;
    //     // $capture->save();

    //     GambarKaryawan::create([
    //         'karyawan_id' => $karyawanId,
    //         'no_foto' => $request->input('no_foto'),
    //         'foto' => $request->$imageName,
    //     ]);

    //     return response()->json(['message' => 'Image saved successfully']);
    // }

    public function storeFoto(Request $request, $karyawanId)
    {
        // Validasi file yang diupload
        $request->validate([
            'foto' => 'required|string', // Pastikan foto adalah string (data URI)
        ]);

        // Mengambil data URI dari input
        $dataUri = $request->input('foto');

        // Memisahkan metadata dari data URI
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $dataUri = substr($dataUri, strpos($dataUri, ',') + 1);
            $type = strtolower($type[1]); // Mengambil tipe gambar (jpeg, png, dll)

            // Decode base64
            $data = base64_decode($dataUri);

            if ($data === false) {
                return response()->json(['message' => 'Gagal mendekode gambar.'], 400);
            }

            // Membuat nama file yang unik
            $filename = 'pic_' . time() . '.' . $type; // Menggunakan timestamp sebagai nama file
            $filePath = public_path('storage/' . $filename); // Path untuk menyimpan file

            // Menyimpan gambar ke public/storage
            if (file_put_contents($filePath, $data) === false) {
                return response()->json(['message' => 'Gagal menyimpan gambar.'], 500);
            }

            // Simpan nama file ke database
            GambarKaryawan::create([
                'karyawan_id' => $karyawanId, // ID karyawan yang diupload
                'no_foto' => $request->input('no_foto'), // Ambil nomor foto dari input
                'foto' => $filename, // Simpan path file relatif
            ]);
        } else {
            return response()->json(['message' => 'Format gambar tidak valid.'], 400);
        }
    }

    public function getKaryawanByDate(Request $request)
    {
        $request->validate([
            'date_nik' => 'required|date',
        ]);

        // Mengambil data dari tabel karyawan dengan eager loading gambar_karyawan  
        $karyawans = GambarKaryawan::with('karyawan')
            ->whereDate('updated_at', $request->date_nik)
            ->get('gambar_karyawan.*');

        return datatables()::of($karyawans)
            ->addColumn('no', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->id : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('nama', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->nama : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('level', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->level : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('departemen', function ($gambar) {
                return $gambar->karyawan ? $gambar->karyawan->departemen : 'Tidak Diketahui'; // Menampilkan nama karyawan  
            })
            ->addColumn('created_at', function ($gambar) {
                return $gambar->created_at->format('Y-m-d H:i:s'); // Format tanggal  
            })

            ->rawColumns(['foto']) // Pastikan 'foto' di sini    
            ->make(true);
    }
}
