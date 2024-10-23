<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'department_id', 'shift_start', 'shift_end', 'date'];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
