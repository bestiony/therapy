<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVersion extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'instructor_id',
        'version',
        'status',
        'details',
    ];
    protected $casts = [
        'details'=>'array',
    ];
<<<<<<< HEAD
<<<<<<< HEAD
=======
    public function course(){
        return $this->belongsTo(Course::class);
    }
>>>>>>> refs/remotes/origin/temporary
=======
    public function course(){
        return $this->belongsTo(Course::class);
    }
=======
>>>>>>> 0116a5f (merge)
>>>>>>> 76a5563 (yet another merge)
}
