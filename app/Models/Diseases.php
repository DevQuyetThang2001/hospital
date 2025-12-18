<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diseases extends Model
{
    protected $table = 'diseases';

    protected $fillable = [
        'name',
        'department_id',
        'description',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_diseases', 'disease_id', 'doctor_id');
    }
}
