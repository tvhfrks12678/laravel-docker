<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'rhyme_id', 'content'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function rhyme()
    {
        return $this->belongsTo(Rhyme::class);
    }
}
