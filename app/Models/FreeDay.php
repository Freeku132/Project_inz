<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeDay extends Model
{
    use HasFactory;

    protected $guarded=[];

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }
}
