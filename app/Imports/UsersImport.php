<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    public function model(array $row)
    {
        return new User([
            'name' => $row[0], // Excel columns: name, bisu_email, student_id, college_id, year_level_id, section_id
            'bisu_email' => $row[1],
            'student_id' => $row[2],
            'password' => Hash::make($row[2]),
            'college_id' => $row[3] ?? null,
            'year_level_id' => $row[4] ?? null,
            'section_id' => $row[5] ?? null,
        ]);
    }
}