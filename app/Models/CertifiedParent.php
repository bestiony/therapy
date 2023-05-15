<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CertifiedParent extends Model
{
    use HasFactory;
    protected static function booted()
    {
        parent::booted();
        self::creating(function ($model) {
            $model->uuid =  Str::uuid()->toString();
        });
    }
    protected $fillable = [
        'uuid',
        'user_id',
        'organization_id',
        'country_id',
        'province_id',
        'state_id',
        'city_id',
        'first_name',
        'last_name',
        'professional_title',
        'phone_number',
        'postal_code',
        'address',
        'consultancy_area',
        'about_me',
        'gender',
        'social_link',
        'slug',
        'is_private',
        'remove_from_web_search',
        'status',
        'is_offline',
        'offline_message',
        'video',
        'intro_video_check',
        'youtube_video_id',
        'consultation_available',
        'rank',
        'organization_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
