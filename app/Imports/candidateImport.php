<?php

namespace App\Imports;

use App\Models\KaryawanBaru;
use App\Models\Posisi;
use App\Models\Departemen;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CandidateImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Check if the 'level' exists in the database
        $level = Posisi::where('level', $row['level'])->first();

        // If the level exists, get its ID; otherwise, set it to null or handle accordingly
        $levelId = $level ? $level->id : null;

        // Check if the 'level' exists in the database
        $department = Departemen::where('job_department', $row['departemen'])->first();

        // If the level exists, get its ID; otherwise, set it to null or handle accordingly
        $departmentId = $department ? $department->id : null;

        return new KaryawanBaru([
            'nama' => $row['nama'],
            'level' => $levelId,
            'workplace' => $departmentId,
            'tempat_lahir' => $row['kota_lahir'],
            'tgl_lahir' => Date::excelToDateTimeObject($row['tanggal_lahir']),
            'tgl_masuk' => Date::excelToDateTimeObject($row['tanggal_masuk']),
        ]);
    }

    // public function headingRow(): int
    // {
    //     return 2;
    // }
}
