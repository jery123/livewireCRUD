<?php

namespace App\Exports;

use App\Models\Students;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class StudentExport implements FromCollection, WithStrictNullComparison, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Students::all();
        // return Students::all([
        //     'id','name', 'email','phone',''
        // ]);
    }
    public function headings() : array
    {
        return [
            'StudentID',
            'StudentName',
            'StudentE-mail',
            'StudentPhone-nuber',
            'StudentOf'
        ];
    }

    public function map($row) : array
    {
        return [
            $row->id,
            $row->name,
            $row->email,
            $row->phone,
            $row->user->name
        ];
    }
}
