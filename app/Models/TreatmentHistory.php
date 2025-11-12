<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentHistory extends Model
{
     use HasFactory;

    protected $table = 'treatment_histories'; // tên bảng

    protected $fillable = [
        'medical_record_id',
        'treatment',
        'note',
    ];


     public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id');
    }
}
