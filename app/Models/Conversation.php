<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable=[
        'patient_id',
        'therapist_id',
        'order_id',
    ];
    public function messages(){
        return $this->hasMany(Messages::class);
    }
    public function therapist(){
        return $this->hasOne(User::class,'id','therapist_id');
    }
    public function patient(){
        return $this->hasOne(User::class,'id','patient_id');
    }
    public function order(){
        return $this->hasOne(Order::class,'id','order_id');
    }
}
