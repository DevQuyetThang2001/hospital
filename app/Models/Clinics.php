<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinics extends Model
{
    protected $table = 'clinics';

    protected $fillable = [
        'name',
        'department_id',
        'status',
        'quantity',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function doctorSchedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'clinic_id');
    }
    
}
