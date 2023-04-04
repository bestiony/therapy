<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDelete extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'instructor_id',
        'reason',
    ];
    public function course(){
        return $this->belongsTo(Course::class);
    }
}
