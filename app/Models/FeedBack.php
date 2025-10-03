<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
     protected $table = 'feedbacks';
      public function patient(){
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
}
