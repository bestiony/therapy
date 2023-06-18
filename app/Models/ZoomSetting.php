<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'api_key',
        'api_secret',
        'timezone',
        'host_video',
        'participant_video',
        'waiting_room',
        'status',
        'username',
        'password',
        'account_id',
        'access_token',
        'token_expires_at',
    ];
}
