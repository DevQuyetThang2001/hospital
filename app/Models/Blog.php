<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class Blog extends Model
{
    protected $table = 'blog';
    use HasFactory, Notifiable;

    protected $fillable = [
        'title',
        'description',
        'doctor_id',
    ];



    public static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });

        static::updating(function ($blog) {
            $blog->slug = Str::slug($blog->title);
        });
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'blog_id');
    }
}
