<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoComment extends Model
{
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function video()
    {
        return $this->belongsTo(Video::class);
    }

    public function videoCommentReplies()
    {
        return $this->hasMany(VideoComment::class, 'parent_id', 'id');
    }

    public function scopeActive()
    {
        return $this->where('status', 1);
    }

}
