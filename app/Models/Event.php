<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'place',
        'date',
        'desc',
        'gmaps',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
