<?php


namespace App\Http\Livewire;


use Livewire\Component;
use App\Models\Students;
use App\Exports\StudentExport;
use App\Imports\StudentImport;
use Maatwebsite\Excel\Facades\Excel;
use phpDocumentor\Reflection\Types\This;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;


class StudentsComponent extends Component
{
    use WithFileUploads;

    public $student_id, $name, $email, $phone, $user_id, $student_edit_id, $student_delete_id, $student;


    public $view_student_id, $view_student_name, $view_student_email, $view_student_phone, $view_student_user_id;
    public $student_import = null;

    public $searchTerm;


    //Input fields on update validation
    public function updated($fields)
    {
        $this->validateOnly($fields, [
            'student_id' => 'required|unique:students,student_id,' . $this->student_edit_id . '', //Validation with ignoring own data
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);
    }



    public function storeStudentData()
    {
        //on form submit validation
        $this->validate([
            'student_id' => 'required|unique:students', //students = table name
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);


        //Add Student Data
        $student = new Students();
        $student->student_id = $this->student_id;
        $student->name = $this->name;
        $student->email = $this->email;
        $student->phone = $this->phone;
        $student->user_id = auth()->user()->id;


        $student->save();


        session()->flash('message', 'New student has been added successfully');


        $this->student_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';


        //For hide modal after add student success
        $this->dispatchBrowserEvent('close-modal');
    }


    public function resetInputs()
    {
        $this->student_id = '';
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->student_edit_id = '';
    }


    public function close()
    {
        $this->resetInputs();
    }


    public function editStudents($id)
    {
        $student = Students::where('id', $id)->first();


        $this->student_edit_id = $student->id;
        $this->student_id = $student->student_id;
        $this->name = $student->name;
        $this->email = $student->email;
        $this->phone = $student->phone;


        $this->dispatchBrowserEvent('show-edit-student-modal');
    }

    public function editStudentData()
    {
        //on form submit validation
        $this->validate([
            'student_id' => 'required|unique:students,student_id,' . $this->student_edit_id . '', //Validation with ignoring own data
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|numeric',
        ]);


        $student = Students::where('id', $this->student_edit_id)->first();
        $student->student_id = $this->student_id;
        $student->name = $this->name;
        $student->email = $this->email;
        $student->phone = $this->phone;


        $student->save();


        session()->flash('message', 'Student has been updated successfully');


        //For hide modal after add student success
        $this->dispatchBrowserEvent('close-modal');
    }


    //Delete Confirmation
    public function deleteConfirmation($id)
    {
        $this->student_delete_id = $id; //student id


        $this->dispatchBrowserEvent('show-delete-confirmation-modal');
    }


    public function deleteStudentData()
    {
        $student = Students::where('id', $this->student_delete_id)->first();
        $student->delete();


        session()->flash('message', 'Student has been deleted successfully');


        $this->dispatchBrowserEvent('close-modal');


        $this->student_delete_id = '';
    }


    public function cancel()
    {
        $this->student_delete_id = '';
    }


    public function viewStudentDetails($id)
    {
        $student = Students::where('id', $id)->first();


        $this->view_student_id = $student->student_id;
        $this->view_student_name = $student->name;
        $this->view_student_email = $student->email;
        $this->view_student_phone = $student->phone;
        $this->view_student_user_id = $student->user_id;


        $this->dispatchBrowserEvent('show-view-student-modal');
    }


    public function closeViewStudentModal()
    {
        $this->view_student_id = '';
        $this->view_student_name = '';
        $this->view_student_email = '';
        $this->view_student_phone = '';
    }

    /**
     * Export Student data in Excel file
     */
    public function export()
    {

        return Excel::download(new StudentExport, 'students.xlsx');
    }




    /**
     * Import student from Excel file
     */
    public function importShow()
    {
        $this->dispatchBrowserEvent('show-import-student-modal');
    }
    public function importStudent()
    {
        $this->validate([
            'student_import' => 'required|mimes:xlsx,csv|max:500',
        ]);
        // dd(request()->all());
        Excel::import(new StudentImport, $this->student_import);
        return redirect('/students');
    }

    public function search()
    {
        $students = Students::where('name', 'like', '%' . $this->searchTerm . '%')->orWhere('email', 'like', '%' . $this->searchTerm . '%')->orWhere('student_id', 'like', '%' . $this->searchTerm . '%')->orWhere('phone', 'like', '%' . $this->searchTerm . '%')->get();

    }

    public function render()
    {
        //Get all students
        $students = Students::where('name', 'like', '%' . $this->searchTerm . '%')->orWhere('email', 'like', '%' . $this->searchTerm . '%')->orWhere('student_id', 'like', '%' . $this->searchTerm . '%')->orWhere('phone', 'like', '%' . $this->searchTerm . '%')->get();


        return view('livewire.students-component', ['students' => $students])->layout('livewire.layouts.base');
    }
}
