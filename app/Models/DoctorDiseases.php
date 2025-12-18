<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorDiseases extends Model
{
    use HasFactory;
    protected $table = 'doctor_diseases';
    protected $fillable = [
        'doctor_id',
        'disease_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    public function disease()
    {
        return $this->belongsTo(Diseases::class, 'disease_id');
    }
    
}
