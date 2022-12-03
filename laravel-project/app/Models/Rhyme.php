<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rhyme extends Model
{
    use HasFactory;

    protected $fillable = ['content'];
}
