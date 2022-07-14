<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Students extends Model
{
    use HasFactory;
    protected $fillable = [
        'id','student_id','name','email',
        'phone','user_id'
    ];
    protected $table = 'students';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
