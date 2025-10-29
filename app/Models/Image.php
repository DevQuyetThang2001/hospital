<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $table = 'images';
    protected $fillable = ['blog_id', 'image'];



    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
}
