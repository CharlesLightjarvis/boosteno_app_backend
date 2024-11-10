<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'pdf_path',
        'user_id', // Assure-toi que user_id est fillable
    ];

    protected $casts = [
        //    
    ];

    public function classes()
    {
        return $this->belongsToMany(Classe::class);
    }
}
