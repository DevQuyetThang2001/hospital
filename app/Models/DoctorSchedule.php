<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules';

    protected $fillable = ['doctor_id', 'schedule_id', 'day_of_week','limit_per_hour'];


    public function doctor(): BelongsTo {
        return $this->belongsTo(Doctor::class);
    }

    public function schedule(): BelongsTo {
        return $this->belongsTo(related: Schedule::class);
    }
}
