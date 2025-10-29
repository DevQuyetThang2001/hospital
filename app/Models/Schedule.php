<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Schedule extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'schedules';


    public function doctor()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_schedules')
            ->withPivot('day_of_week')
            ->withTimestamps();
    }

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'schedule_id');
    }
}
