<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'details',
        'image',
        'video',
        'status',
        'video_category_id',
        'description',
        'keywords',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function category()
    {
        return $this->belongsTo(VideoCategory::class, 'video_category_id', 'id');
    }


    public function tags()
    {
        return $this->hasMany(VideoTag::class);
    }


    public function tagName(){
        return $this->belongsToMany(Tag::class, 'video_tags');
    }

    public function videoComments()
    {
        return $this->hasMany(VideoComment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getImagePathAttribute()
    {
        if ($this->image)
        {
            return $this->image;
        } else {
            return 'uploads/default/video.webp';
        }
    }


    protected static function boot()
    {
        parent::boot();
        self::creating(function($model){
            $model->uuid =  Str::uuid()->toString();
            $model->user_id =  auth()->id();
            $model->status =  auth()->user()->is_admin() ? 1 : 0;
        });
    }


}
