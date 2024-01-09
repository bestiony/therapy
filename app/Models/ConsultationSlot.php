<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultationSlot extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function canBeBooked(string $bookingDate) : bool
    {
        $consultationSlotStartingTime = explode("-", $this->time)[0];
        $startingTime = Carbon::parse(
            $bookingDate . " " .
                $consultationSlotStartingTime
        );
        ;
        return Carbon::now()->lte($startingTime);
    }
}
