<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyLecturerEmail extends Model
{
    use HasFactory;
    protected $fillable = ['lecturer_uniqueid', 'token'];
}
