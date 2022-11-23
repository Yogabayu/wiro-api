<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'address',
        'photo',
        'gmaps',
        'contact',
        'entry_fee',
    ];
}
