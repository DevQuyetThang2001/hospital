<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, Notifiable;
    protected $table = 'patients';



    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function department(){
        return $this->belongsTo(Department::class,'department_id','id');
    }
}
