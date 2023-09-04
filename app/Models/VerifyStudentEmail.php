<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyStudentEmail extends Model
{
    use HasFactory;

    protected $fillable = ['user_uniqueid','token','created_at'];
}
