<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Department extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];
    protected $table = 'departments';



    public function doctor(){
        return $this->hasMany(Doctor::class,'department_id','id');
    }


    public function room(){
        return $this->hasMany(Room::class,'department_id','id');
    }


    public function patient(){
        return $this->hasMany(Patient::class,'department_id','id');
    }
}
