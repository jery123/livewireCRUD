<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Students;
use Illuminate\Foundation\Auth\User;
use Maatwebsite\Excel\Concerns\ToArray;

class StudentImport implements ToArray, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function array(array $daata)
    {
        // dd($daata);
        foreach ($daata as $student) {
            $user_id = User::where('name', $student['studentof'])->first();
            // dd($user_id->id);
            Students::create([
                // 'id' => $student['studentid'],
                // 'student_id' => $student['studentid'],
                'name' => $student['studentname'],
                'email' => $student['studente_mail'],
                'phone' => $student['studentphone_nuber'],
                'user_id' => $user_id->id,
                ''
            ]);
        }
    }
}
