<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'video_id',
        'tag_id'
    ];


    public function tag(){
        return $this->belongsTo(Tag::class);
    }

}
