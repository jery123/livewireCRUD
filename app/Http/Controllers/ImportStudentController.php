<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportStudentController extends Controller
{

    /**
     * Import student from Excel file
     */
    public function importShow()
    {
        $this->dispatchBrowserEvent('show-import-student-modal');
    }
    public function importStudent()
    {
        dd(request()->all());
        // Excel::import(new StudentImport, request()->file('file'));
        return redirect('/students');
    }


}
