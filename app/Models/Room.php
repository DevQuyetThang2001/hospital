<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Room extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'rooms';




    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
