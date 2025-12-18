<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $table = 'admissions';
    protected $fillable = [
        'patient_id',
        'room_id',
        'admission_date',
        'discharge_date',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
