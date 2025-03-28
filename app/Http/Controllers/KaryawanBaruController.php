<?php

namespace App\Http\Controllers;

use App\Imports\CandidateImport;
use Illuminate\Http\Request;
use App\Models\KaryawanBaru;
use App\Models\GambarKaryawan;
use App\Models\Posisi;
use App\Models\Departemen;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

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
        return $karyawans;
    }

    public function show($id)
    {
        return KaryawanBaru::with('gambarKaryawan', 'posisi', 'departemen')->findOrFail($id);
        return response()->json($karyawan);
    }

    public function update(Request $request, $id)
    {
        $data = [
            'id' => $id,
            'nik' => $request->nik,
            'nama' => $request->nama,
            'level' => $request->level,
            'workplace' => $request->workplace,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'tgl_masuk' => $request->tgl_masuk,
        ];
        DB::table('karyawan_barus')
            ->where('id', $id)
            ->update($data);
        // Mengambil data URI dari input
        if ($request->has('foto') && $request->has('no_foto')) {
            $dataUri = $request->input('foto');
            // Memisahkan metadata dari data URI
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
                $dataUri = substr($dataUri, strpos($dataUri, ',') + 1);
                $type = strtolower($type[1]); // Mengambil tipe gambar (jpeg, png, dll)                
                // Decode base64
                $data = base64_decode($dataUri, true);
                if ($data === false) {
                    return response()->json(['message' => 'Gagal mendekode gambar.'], 400);
                } else {
                    // Membuat nama file yang unik
                    $filename = 'pic_' . time() . '.' . $type; // Menggunakan timestamp sebagai nama file
                    $filePath = public_path('storage/' . $filename); // Path untuk menyimpan file
                    // Menyimpan gambar ke public/storage
                    if (file_put_contents($filePath, $data) === false) {
                        return response()->json(['message' => 'Gagal menyimpan gambar.'], 500);
                    }
                    $get_foto = DB::table('gambar_karyawan')->where('karyawan_id', '=', $request->id);
                    if ($get_foto->count() > 0) {
                        DB::table('gambar_karyawan')
                            ->where('karyawan_id', $id)
                            ->update([
                                'no_foto' => $request->input('no_foto'), // Ambil nomor foto dari input
                                'foto' => $filename, // Simpan path file relatif
                            ]);
                    } else {
                        GambarKaryawan::create([
                            'karyawan_id' => $id, // ID karyawan yang diupload
                            'no_foto' => $request->input('no_foto'), // Ambil nomor foto dari input
                            'foto' => $filename, // Simpan path file relatif
                        ]);
                    }
                }
                $karyawan_barus = DB::table('karyawan_barus')->get();
                return response()->json($karyawan_barus);
            } else {
                $karyawan_barus = DB::table('karyawan_barus')->get();
                return response()->json($karyawan_barus);
            }
        }
    }

    public function destroy($id)
    {
        KaryawanBaru::destroy($id);
        return response()->json(['success' => 'User deleted successfully.']);
    }

    public function deleteSelected(Request $request)
    {
        $ids = $request->input('ids'); // Get the array of IDs from the request
        KaryawanBaru::with('gambarKaryawan', 'posisi', 'departemen')->whereIn('id', $ids)->delete(); // Delete the records

        return response()->json(['success' => 'Records deleted successfully.']);
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

    // public function getPhotoList()
    // {

    //     $karyawans = GambarKaryawan::with('karyawan')->select('gambar_karyawan.*'); // Ambil semua data pengguna
    //     return datatables()->of($karyawans) // Menggunakan DataTables
    //         ->addColumn('karyawan_id', function ($gambar) {
    //             return $gambar->karyawan ? $gambar->karyawan->nama : 'Tidak Diketahui'; // Menampilkan nama karyawan  
    //         })
    //         ->addColumn('karyawan_position', function ($gambar) {
    //             return $gambar->karyawan ? $gambar->karyawan->level : 'Tidak Diketahui'; // Menampilkan nama karyawan  
    //         })
    //         ->addColumn('karyawan_departemen', function ($gambar) {
    //             return $gambar->karyawan ? $gambar->karyawan->departemen : 'Tidak Diketahui'; // Menampilkan nama karyawan  
    //         })
    //         ->addColumn('created_at', function ($gambar) {
    //             return $gambar->created_at->format('Y-m-d H:i:s'); // Format tanggal  
    //         })
    //         ->addColumn('updated_at', function ($gambar) {
    //             return $gambar->updated_at->format('Y-m-d H:i:s'); // Format tanggal  
    //         })
    //         ->addColumn('action', function ($karyawans) {
    //             return '<button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Edit</button>
    //                     <button class="btn btn-primary btn-sm edit" data-id="' . $karyawans->id . '">Take Picture</button>
    //                     <button class="btn btn-danger btn-sm delete" data-id="' . $karyawans->id . '">Delete</button>';
    //         })
    //         ->rawColumns(['action'])
    //         ->make(true); // Menggunakan DataTables
    // }

    public function storeFoto(Request $request, $karyawanId)
    {
        // Validasi file yang diupload
        $request->validate([
            'foto' => 'nullable|string', // Pastikan foto adalah string (data URI)
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

    public function updateNoFoto($request, $id)
    {
        // return $request;
        // return response()->json(['message' => 'Format gambar  valid.']);
        // Validasi file yang diupload
        // $request->validate([
        //     'no_foto' => '',
        // ]);
        // $gambar = GambarKaryawan::findOrFail($id);
        // $gambar->update($request);
        // $gambar->update($data->all());
        DB::table('karyawan_barus')
            ->where('id', $id)
            ->update($request);
    }

    public function updateFoto($request, $karyawanId)
    {
        // Validasi file yang diupload
        // $request->validate([
        //     'foto' => 'nullable|string', // Pastikan foto adalah string (data URI)
        // ]);
        // Mengambil data URI dari input
        $dataUri = $request->input('foto');
        // Memisahkan metadata dari data URI
        if (preg_match('/^data:image\/(\w+);base64,/', $dataUri, $type)) {
            $dataUri = substr($dataUri, strpos($dataUri, ',') + 1);
            $type = strtolower($type[1]); // Mengambil tipe gambar (jpeg, png, dll)

            // Decode base64
            $data = base64_decode($dataUri);

            if ($data === false) {
                return response()->json(['message' => 'Gagal mendekode gambar.'], 401);
            }

            // Membuat nama file yang unik
            $filename = 'pic_' . time() . '.' . $type; // Menggunakan timestamp sebagai nama file
            Log::info('Generated filename: ' . $filename); // Debugging the filename variable
            $filePath = public_path('storage/' . $filename); // Path untuk menyimpan file

            // Menyimpan gambar ke public/storage
            if (file_put_contents($filePath, $data) === false) {
                return response()->json(['message' => 'Gagal menyimpan gambar.'], 501);
            }

            // Update nama file di database
            $gambar = GambarKaryawan::where('karyawan_id', $karyawanId)->first();
            if ($gambar) {
                $gambar->update([
                    'foto' => $filename // Update path file relatif
                ]);
            } else {
                GambarKaryawan::create([
                    'karyawan_id' => $karyawanId, // ID karyawan yang diupload
                    'no_foto' => $request->input('no_foto'), // Ambil nomor foto dari input
                    'foto' => $filename, // Simpan path file relatif
                ]);
            }
        } else {
            return response()->json(['message' => 'Format gambar tidak valid.'], 400);
        }
    }

    public function autocomplete(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'tgl_lahir' => 'required|date',
        ]);
        // Ambil data berdasarkan nama dan tanggal lahir
        $data = KaryawanBaru::with(['gambarkaryawan', 'posisi', 'departemen'])->where('nama', 'LIKE', '%' . $request->nama . '%')
            ->where('tgl_lahir', $request->tgl_lahir)
            ->get();
        return response()->json($data);
    }

    public function autocomplete2(Request $request)
    {
        // Ambil data berdasarkan nama dan tanggal lahir
        $calon_karyawan = KaryawanBaru::with('posisi', 'departemen')->find($request->id);
        $get_foto = GambarKaryawan::where('karyawan_id', '=', $request->id);
        $foto = [
            'no_foto' => NULL,
            'foto' => NULL,
        ];
        if ($get_foto->count() > 0) {
            $foto = [
                'no_foto' => $get_foto->first()->no_foto,
                'foto' => $get_foto->first()->foto,
            ];
        }
        $data = [$calon_karyawan, $foto];
        return response()->json($data);
    }

    public function datefilter(Request $request)
    {
        $date = $request->input('date');
        $karyawans = KaryawanBaru::with(['gambarkaryawan', 'posisi', 'departemen'])
            ->whereDate('tgl_masuk', $date)
            ->whereHas('gambarKaryawan')
            ->where('status', 1)
            ->get();

        return response()->json(['data' => $karyawans]);
    }

    function updatenik(Request $request)
    {
        $tglmasuk = $request->input('tglmasuk');
        $prefix = $request->input('prefix');
        $newnik = $request->input('newnik');

        $karyawans = KaryawanBaru::where('tgl_masuk', $tglmasuk)->get();
        // Check if the new NIK already exists
        $existingKaryawan = KaryawanBaru::where('nik', $prefix . $newnik)->first();

        foreach ($karyawans as $karyawan) {
            // Check if the candidate has withdrawn (status = 2)
            if ($karyawan->status == 2) {
                return response()->json(['message' => 'Kandidat ' . $karyawan->nama . ' mengundurkan diri.'], 400);
            } else if ($existingKaryawan) {
                return response()->json(['message' => 'NIK already exists for another employee.'], 400);
            } else {
                // Update the NIK for the current employee
                $karyawan->nik = $prefix . $newnik;
                $karyawan->save();
                $newnik++; // Increment the NIK number for the next employee
            }
        }

        return response()->json(['message' => 'NIKs updated successfully.']);
    }

    public function getKaryawan($id)
    {
        // Fetch the employee data based on the ID
        $karyawan = KaryawanBaru::with('gambarkaryawan', 'posisi', 'departemen')->find($id);

        if ($karyawan) {
            return response()->json([
                'data' => [
                    'id' => $karyawan->id,
                    'nik' => $karyawan->nik,
                    'nama' => $karyawan->nama,
                    'gambarkaryawan' => $karyawan->gambarkaryawan,
                    'posisi' => $karyawan->posisi,
                    'departemen' => $karyawan->departemen,
                ]
            ]);
        }

        return response()->json(['error' => 'Karyawan tidak ditemukan.'], 404);
    }

    public function updateStatus(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'employees' => 'required|array',
            'employees.*.nik' => 'required|string', // Assuming 'nik' is a string
            // Add other validation rules as necessary
        ]);

        // Loop through each employee and update their status
        foreach ($request->employees as $employeeData) {
            $employee = KaryawanBaru::where('nik', $employeeData['nik'])->first();
            if ($employee) {
                // Update the employee's status or any other fields as necessary
                $employee->status = 3; // Change this to the actual status you want to set
                $employee->save();
            }
        }

        // Return a success response
        return response()->json(['message' => 'Employee status updated successfully.']);
    }

    public function updateStatusMasuk(Request $request, $id)
    {
        // Find the employee by ID
        $karyawan = KaryawanBaru::find($id);

        if ($karyawan) {
            // Update the status (assuming you have a 'status' field)
            $karyawan->status = 2; // or whatever status you want to set
            $karyawan->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Employee not found.'], 404);
    }

    public function import_excel(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $file->move('storage', $nama_file);

        // import data
        Excel::import(new CandidateImport, public_path('/storage/' . $nama_file));

        // notifikasi dengan session
        Session::flash('toast_success', 'Data Siswa Berhasil Diimport!');

        // alihkan halaman kembali
        return redirect('/candidate');
    }
}
