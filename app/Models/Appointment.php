<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'schedule_id',
        'username',
        'email',
        'appointment_date',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id', 'id');
    }

    public function bookedBy()
    {
        return $this->belongsTo(User::class, 'booked_by');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'schedule_id', 'id');
    }
}
