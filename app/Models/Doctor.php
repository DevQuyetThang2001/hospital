<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function schedule()
    {
        return $this->belongsToMany(Schedule::class, 'doctor_schedules')
            ->withPivot('day_of_week')
            ->withTimestamps();
    }
    public function diseases()
    {
        return $this->belongsToMany(Diseases::class, 'doctor_diseases', 'doctor_id', 'disease_id');
    }
}
