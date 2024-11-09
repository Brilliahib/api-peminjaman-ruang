<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function bookings() {
        return $this->belongsToMany(Booking::class, 'booking_students');
    }
}
