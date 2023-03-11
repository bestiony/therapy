<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'conversation_id',
        'content',
        'file',
    ];
    public function sender(){
        return $this->belongsTo(User::class,'sender_id','id');
    }
}
