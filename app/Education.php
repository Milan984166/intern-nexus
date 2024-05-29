<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'user_id', 'degree', 'program', 'board', 'institute', 'student_here', 'year', 'month', 'marks_unit', 'marks', 'created_by', 'updated_by'
    ];

    protected $hidden = [
        'created_by', 'updated_by', 'created_at', 'updated_at'
    ];
}
